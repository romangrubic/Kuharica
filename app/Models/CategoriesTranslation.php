<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CategoriesTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories_translations';

    public $timestamps = false;
    protected $fillable = ['title'];

    public static function getTitle($categoryId)
    {
        return DB::table('categories_translations')
            ->select('title')
            ->where('categories_id', '=', $categoryId)
            ->where('locale', '=', App::getLocale())
            ->first();
    }
}
