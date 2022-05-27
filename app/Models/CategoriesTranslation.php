<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\BelongsTo};

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

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }
}
