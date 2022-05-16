<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Tags extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    public $translatedAttributes = ['title'];
    protected $fillable = ['slug'];

    public static function getTag($value)
    {
        return DB::table('tags')
            ->where('id', '=', $value)
            ->first();
    }
}
