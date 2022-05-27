<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo};

class MealsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_translations';
    protected $fillable = ['title', 'description'];

    public function meals(): BelongsTo
    {
        return $this->belongsTo(Meals::class);
    }
}
