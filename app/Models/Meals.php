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
        return $this->hasManyThrough(Tags::class, MealsTags::class, 'meals_id', 'tags_id');
    }

    public static function readMeals($parameters)
    {
//       If there is tags list in GET params, $multiple array contains all meals that have those tags
        $multiple = Meals::getArray($parameters);
        
        $meals = DB::table('meals')
            ->select('meals.id', 'title', 'description', 'status', 'category_id')
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
            ->where(function ($query) use ($parameters, $multiple) {
                if (isset($parameters['tags'])) {
                    $query->whereIn('id', $multiple);
                }
            })
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

//        Count for meta['totalItems'].
//        From dd($meals)->total is not showing correct number of meals, so I had to make another query
        $countMeals = $meals['total'];

        return [$meals, $countMeals];
    }

//        Forgive me for this. For the love of God, I couldn't find a way to do this in query builder.
//        So I made this. Pretty ugly but working.
    public static function getArray($parameters)
    {
        $multiple = [];
        if (isset($parameters['tags'])) {
            $array = [];
            $count = 0;
            foreach ($parameters['tags'] as $tag) {
                $o = MealsTags::getMealsWithTags($tag);
                $count++;
                foreach ($o as $ok) {
                    foreach ($ok as $k => $v) {
                        $array[] = $v;
                    }
                }
            }
            $newArray = (array_count_values($array));

            foreach ($newArray as $k => $v) {
                if ($v >= $count ) {
                    $multiple[] = $k;
                }
            }
        }
        return $multiple;
    }
}
