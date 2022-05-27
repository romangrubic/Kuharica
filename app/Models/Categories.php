<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\HasMany};
use Illuminate\Support\Facades\{
    App,
    DB
};

class Categories extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    protected $with = ['categoriesTranslations'];
    protected $fillable = ['slug'];

    public function meals(): HasMany
    {
        return $this->hasMany(Meals::class);
    }

    public function categoriesTranslations(): HasMany
    {
        return $this->hasMany(CategoriesTranslation::class)->where('locale', '=', App::getLocale());
    }
}
