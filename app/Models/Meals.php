<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Route;

class Meals extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals';

    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['slug'];

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'meals_tags', 'meals_id', 'tags_id');
    }

    public static function readMeals($parameters)
    {
        $meals = DB::table('meals')
            ->select('id', 'title', 'description', 'status', 'category_id')
//            ->join('meals_tags', 'meals_tags.meals_id', '=', 'meals.id')
            ->where(function ($query) use ($parameters) {
                if (!isset($parameters['category'])) {
                    return;
                }
                switch ($parameters['category']) {
                    case('NULL'):
                        $query->whereNull('category_id');
                        break;
                    case('!NULL'):
                        $query->whereNotNull('category_id');
                        break;
                    default:
                        $query->where('category_id', '=', $parameters['category']);
                        break;
                }
            })
//            ->where(function ($query) use ($parameters) {
//                if (isset($parameters['tags'])) {
//                    foreach($parameters['tags'] as $tag){
//                        $query = $query->where('tags_id','=', $tag);
//                        };
//                    }
//            })
            ->where(function ($query) use ($parameters) {
                if (isset($parameters['diff_time'])) {
                    $query = $query->whereDate('created_at', '>=', date('Y-m-d H:i:s', $parameters['diff_time']));
                };
            })
            ->paginate(((isset($parameters['per_page'])) ? $parameters['per_page'] : 10), '[*]', 'page', ((isset($parameters['page'])) ? $parameters['page'] : 1))
            ->toArray();

//        Get translations and put them in their place
        foreach ($meals['data'] as $meal) {
            $translation = MealsTranslation::getTitleAndDescription($meal->id);
            $meal->title = $translation->title;
            $meal->description = $translation->description;
        }

//        Count for meta['totalItems']
        $countMeals = DB::table('meals')
            ->select('id', 'title', 'description', 'status', 'category_id')
            ->where(function ($query) use ($parameters) {
                if (!isset($parameters['category'])) {
                    return;
                }
                switch ($parameters['category']) {
                    case('NULL'):
                        $query->whereNull('category_id');
                        break;
                    case('!NULL'):
                        $query->whereNotNull('category_id');
                        break;
                    default:
                        $query->where('category_id', '=', $parameters['category']);
                        break;
                }
            })
            ->where(function ($query) use ($parameters) {
                if (isset($parameters['diff_time'])) {
                    $query = $query->whereDate('created_at', '>=', date('Y-m-d H:i:s', $parameters['diff_time']));
                };
            })
            ->get()->count();

//        dd($meals) is showing me tha data I need to mimic the given response from the Task;

//        Formatting meta part
        return [$meals, $countMeals];
    }
}
