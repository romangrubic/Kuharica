<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Categories extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    public $translatedAttributes = ['title'];
    protected $fillable = ['slug'];

    public static function getCategory($value)
    {
        return DB::table('categories')->first();
    }
}
