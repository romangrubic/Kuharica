<?php

/**
 * This file contains model for ingredients_translations table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsTo
};

/**
 * IngredientsTranslation is a model class for ingredients_translations table.
 */
class IngredientsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * Relation with Ingredients model.
     *
     * @return BelongsTo
     */
    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class);
    }
}
