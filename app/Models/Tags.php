<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsToMany,
    Relations\HasMany
};
use Illuminate\Support\Facades\App;

class Tags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tagsTranslations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug'];

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meals::class, 'meals_tags', 'tags_id', 'meals_id');
    }

    public function tagsTranslations(): HasMany
    {
        return $this->hasMany(TagsTranslation::class)->where('locale', '=', App::getLocale());
    }
}
