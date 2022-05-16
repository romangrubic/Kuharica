<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TagsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags_translations';

    public $timestamps = false;
    protected $fillable = ['title'];

    public static function getTitle($tagId)
    {
        return DB::table('tags_translations')
            ->select('title')
            ->where('tags_id', '=', $tagId)
            ->where('locale', '=', App::getLocale())
            ->first();
    }
}
