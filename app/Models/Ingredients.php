<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsToMany,
    Relations\HasMany
};
use Illuminate\Support\Facades\App;

class Ingredients extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';
    protected $with = ['ingredientsTranslations'];
    protected $fillable = ['slug'];

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meals::class, 'meals_tags', 'ingredients_id', 'meals_id');
    }

    public function ingredientsTranslations(): HasMany
    {
        return $this->hasMany(IngredientsTranslation::class)->where('locale', '=', App::getLocale());
    }
}
