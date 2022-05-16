<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class IngredientsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients_translations';

    public $timestamps = false;
    protected $fillable = ['title'];

    public static function getTitle($ingredientId)
    {
        return DB::table('ingredients_translations')
            ->select('title')
            ->where('ingredients_id', '=', $ingredientId)
            ->where('locale', '=', App::getLocale())
            ->first();
    }
}
