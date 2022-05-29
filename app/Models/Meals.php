<?php

/**
 * This file contains model for Meals table.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
    SoftDeletes,
    Relations\BelongsToMany};
use Illuminate\Support\Facades\{App,
    DB};

/**
 * Meals is a model class for meals table.
 */

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
    protected $with = ['meals_translations'];
    protected $fillable = ['slug'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'meals_tags', 'meals_id', 'tags_id');
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredients::class, 'meals_ingredients', 'meals_id', 'ingredients_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }

    public function meals_translations(): HasMany
    {
        return $this->hasMany(MealsTranslation::class)->where('locale', '=', App::getLocale());
    }

//    For future me: whole point of readMeals is to get list of Id-s so that I can pass it to MealController eloquent query
    public static function readMeals($parameters): array
    {
//        Main query
        $meals = Meals::select('id')
//            ->join('meals_tags', 'meals_tags.meals_id', '=', 'meals.id', 'inner')
            ->where(function ($query) use ($parameters) {
                if (!isset($parameters['diff_time'])) {
                    $query->where('deleted_at', '=', null);
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
            ->when(isset($parameters['tags']), function ($query) use ($parameters) {
                $requestedTagIds = explode(',', $parameters['tags']);
//                dd($parameters['tags']);
                return $query->whereHas(
                    'tags',
                    fn($query) => $query->whereIn('tags_id', $requestedTagIds),
                    '=',
                    count($requestedTagIds)
                );
            })
            ->where(function ($query) use ($parameters) {
                if (isset($parameters['diff_time'])) {
                    $timestamp = Carbon::createFromTimestamp($parameters['diff_time']);
                    $query->where('updated_at', '>=', $timestamp);
                };
            })
            ->paginate(((isset($parameters['per_page'])) ? $parameters['per_page'] : 10), '[*]', 'page', ((isset($parameters['page'])) ? $parameters['page'] : 1))
            ->toArray();

        return $meals;
//        Get translations and put them in their place
        foreach ($meals['data'] as $meal) {
            if (!isset($parameters['diff_time'])) {
                $meal->status = 'created';
            } else {
//                dd($meal);
                if ($meal->created_at == $meal->updated_at) {
                    $meal->status = 'created';
                } else {
                    $meal->status = 'modified';
                }
                if ($meal->deleted_at != null) {
                    $meal->status = 'deleted';
                }
            }
            $translation = MealsTranslation::getTitleAndDescription($meal->id);
            $meal->title = $translation->title;
            $meal->description = $translation->description;
        }

//        Count for meta['totalItems'].
        $countMeals = $meals['total'];

        return [$meals, $countMeals];
    }
}
