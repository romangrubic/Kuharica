<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MealsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_translations';

    public $timestamps = false;
    protected $fillable = ['title', 'description'];

//    Get correct translation for given meal id and the locale!
    public static function getTitleAndDescription($mealId)
    {
        return DB::table('meals_translations')
            ->select('title', 'description')
            ->where('meals_id', '=', $mealId)
            ->where('locale', '=', App::getLocale())
            ->first();
    }
}
