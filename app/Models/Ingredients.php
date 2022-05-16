<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ingredients extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';

    public $translatedAttributes = ['title'];
    protected $fillable = ['slug'];

    public static function getIngredient($value)
    {
        return DB::table('ingredients')
            ->where('id', '=', $value)
            ->first();
    }
}
