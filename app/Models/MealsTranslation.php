<?php

/**
 * This file contains model for meals_translation table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo};

/**
 * MealsTranslation is a model class for meals_translation table.
 */
class MealsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description'];

    /**
     * Relation with Meals model.
     *
     * @return BelongsTo
     */
    public function meals(): BelongsTo
    {
        return $this->belongsTo(Meals::class);
    }
}
