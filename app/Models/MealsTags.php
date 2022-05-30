<?php

/**
 * This file contains model for meals_tags table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model};

/**
 * MealsTags is a model class for meals_tags table.
 */
class MealsTags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_tags';
}
