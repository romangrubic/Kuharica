<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealsIngredients extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_ingredients';

    use HasFactory;
}
