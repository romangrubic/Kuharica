<?php

/**
 * This file contains model for meals_ingredients table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model};

/**
 * MealsIngredients is a model class for meals_ingredients table.
 */
class MealsIngredients extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_ingredients';
}
