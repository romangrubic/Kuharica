<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsTo
};

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

    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class);
    }
}
