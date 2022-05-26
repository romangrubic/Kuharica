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

    public function mealstags()
    {
        return $this->belongsToMany('App\Models\Tags', 'meals_tags', 'meals_id', 'tags_id');
    }

    public static function readMeals($parameters)
    {
//        dd($parameters['tags']);
//        $tags = implode(', ', $parameters['tags']);
//        dd($tags);

//        SELECT  m.*
//        FROM    meals m
//        WHERE   EXISTS  (
//                    SELECT  1
//                    FROM    meals_tags t
//                    WHERE   m.id = t.meals_id
//                    AND     t.tags_id IN (227,25)
//                    HAVING COUNT(1) = 2
//                );


//        Main query
        $meals = DB::table('meals')
            ->select('id', 'title', 'description', 'status', 'category_id')
//            ->join('meals_tags', 'meals_tags.meals_id', '=', 'meals.id', 'inner')
            ->where(function ($query) use ($parameters) {
                if (!isset($parameters['diff_time'])) {
                    $query->where('status', '=', 'created');
                }
            })
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
//            ->whereExists(function ($query) use ($parameters) {
//                if (isset($parameters['tags'])) {
//                    $query->select(DB::raw(1))
//                        ->from('meals_tags')
//                        ->where('meals.id', '=', 'meals_tags.meals_id')
//                        ->whereIn('meals_tags.tags_id', $parameters['tags'])
//                        ->having(DB::raw('COUNT(1)'), '=', count($parameters['tags']));
//                        }
//                })
            ->where(function ($query) use ($parameters) {
                if (isset($parameters['diff_time'])) {
                    $query->whereDate('deleted_at', '>=', date('Y-m-d H:i:s', $parameters['diff_time']))
                        ->orWhereDate('updated_at', '>=', date('Y-m-d H:i:s', $parameters['diff_time']));
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
        $countMeals = $meals['total'];

        return [$meals, $countMeals];
    }

//        For the love of God, I couldn't find a way to do this in query builder.
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
