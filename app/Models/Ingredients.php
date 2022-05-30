<?php

/**
 * This file contains model for ingredients table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsToMany,
    Relations\HasMany
};
use Illuminate\Support\Facades\App;

/**
 * Ingredients is a model class for ingredients table.
 */
class Ingredients extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['ingredientsTranslations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug'];

    /**
     * Relation with Meals model.
     *
     * @return BelongsToMany
     */
    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meals::class, 'meals_tags', 'ingredients_id', 'meals_id');
    }

    /**
     * Relation with IngredientsTranslation model.
     *
     * @return HasMany
     */
    public function ingredientsTranslations(): HasMany
    {
        return $this->hasMany(IngredientsTranslation::class)->where('locale', '=', App::getLocale());
    }
}
