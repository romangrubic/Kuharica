<?php

/**
 * This file contains model for tags_translation table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo};

/**
 * TagsTranslation is a model class for tags_translation table.
 */
class TagsTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * Relation with Tags model.
     *
     * @return BelongsTo
     */
    public function tags(): BelongsTo
    {
        return $this->belongsTo(Tags::class);
    }
}
