<?php

/**
 * This file contains model for meals table.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
    SoftDeletes,
    Relations\BelongsToMany};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;

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

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['meals_translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug'];

    /**
     * Relation with Tags model.
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'meals_tags', 'meals_id', 'tags_id');
    }

    /**
     * Relation with Ingredients model.
     *
     * @return BelongsToMany
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredients::class, 'meals_ingredients', 'meals_id', 'ingredients_id');
    }

    /**
     * Relation with Categories model.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }

    /**
     * Relation with MealsTranslation model.
     *
     * @return HasMany
     */
    public function meals_translations(): HasMany
    {
        return $this->hasMany(MealsTranslation::class)->where('locale', '=', App::getLocale());
    }

    /**
     * Main method for searching meals.
     * Takes parameters from GET request and adds where clauses to query when requested parameter is set.
     * Returns LengthAwarePaginator so that I can access paginate stuff in MealsCollection.
     *
     * @param array $parameters
     * @return LengthAwarePaginator
     */
    public static function readMeals(array $parameters): LengthAwarePaginator
    {
            return Meals::select('*')
                /**
                 * 'category' - INT greater than 0, NULL or !NULL (case-sensitive, no leading zeroes)
                 */
                ->when(isset($parameters['category']), function ($query) use ($parameters) {
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
                /**
                 * 'tags' - string of comma separated integers greater than 0 (1,2,33). Any number of integers.
                 */
                ->when(isset($parameters['tags']), function ($query) use ($parameters) {
                    $requestedTagIds = explode(',', $parameters['tags']);
                    return $query->whereHas(
                        'tags',
                        fn($query) => $query->whereIn('tags_id', $requestedTagIds),
                        '=',
                        count($requestedTagIds)
                    );
                })
                /**
                 * 'with' - string combination of (category|tags|ingredients) with comma separation.
                 *        - Max 3 repetitions (min 1). Can have trailing comma.
                 */
                ->when(isset($parameters['with']), function ($query) use ($parameters) {
                    $with = array_filter(explode(',', $parameters['with']));
                    $query->with($with);
                })
                /**
                 *  'diff_time - UNIX timestamp greater than 0.
                 */
                ->when(isset($parameters['diff_time']), function ($query) use ($parameters) {
                    $timestamp = Carbon::createFromTimestamp($parameters['diff_time']);
                    $query->where('meals.updated_at', '>=', $timestamp)
                        ->withTrashed();
                })
                ->paginate($parameters['per_page'], '[*]', 'page', $parameters['page']);
    }
}
