<?php

/**
 * This file contains model for categories table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\HasMany};
use Illuminate\Support\Facades\App;

/**
 * Categories is a model class for categories table.
 */
class Categories extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['categoriesTranslations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug'];

    /**
     * Relation with Meals model.
     *
     * @return HasMany
     */
    public function meals(): HasMany
    {
        return $this->hasMany(Meals::class);
    }

    /**
     * Relation with CategoriesTranslations model.
     *
     * @return HasMany
     */
    public function categoriesTranslations(): HasMany
    {
        return $this->hasMany(CategoriesTranslation::class)->where('locale', '=', App::getLocale());
    }
}
