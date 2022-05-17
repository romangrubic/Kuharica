<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MealsTags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_tags';

    public static function searchByMealId($value)
    {
        return DB::table('meals_tags')
            ->select('tags_id')
            ->where('meals_id', '=', $value)
            ->get()
            ->toArray();
    }

    public static function getMealsWithTags($tag)
    {
        return DB::table('meals_tags')
            ->select('meals_id')
            ->where('tags_id', '=', $tag)->get()->toArray();
    }
}
