<?php

/**
 * This file contains model for tags table.
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
 * Meals is a model class for meals table.
 */
class Tags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tagsTranslations'];

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
        return $this->belongsToMany(Meals::class, 'meals_tags', 'tags_id', 'meals_id');
    }

    /**
     * Relation with TagsTranslation model.
     *
     * @return HasMany
     */
    public function tagsTranslations(): HasMany
    {
        return $this->hasMany(TagsTranslation::class)->where('locale', '=', App::getLocale());
    }
}
