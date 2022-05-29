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

    public static function readMeals($parameters): LengthAwarePaginator
    {
            return Meals::select('*')
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
                ->when(isset($parameters['tags']), function ($query) use ($parameters) {
                    $requestedTagIds = explode(',', $parameters['tags']);
                    return $query->whereHas(
                        'tags',
                        fn($query) => $query->whereIn('tags_id', $requestedTagIds),
                        '=',
                        count($requestedTagIds)
                    );
                })
                ->when(isset($parameters['with']), function ($query) use ($parameters) {
                    $query->with(explode(',', $parameters['with']));
                })
                ->when(isset($parameters['diff_time']), function ($query) use ($parameters) {
                    $timestamp = Carbon::createFromTimestamp($parameters['diff_time']);
                    $query->where('meals.updated_at', '>=', $timestamp)
                        ->withTrashed();
                })
                ->paginate($parameters['per_page'], '[*]', 'page', $parameters['page']);
    }
}
