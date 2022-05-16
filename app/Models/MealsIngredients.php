<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MealsIngredients extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_ingredients';

    public static function searchByMealId($value)
    {
        return DB::table('meals_ingredients')
            ->select('ingredients_id')
            ->where('meals_id', '=', $value)
            ->get()
            ->toArray();
    }
}
