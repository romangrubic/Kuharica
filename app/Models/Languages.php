<?php

/**
 * This file contains model for languages table.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\{Collection,
    Factories\HasFactory,
    Model};

/**
 * Languages is a model class for languages table.
 */
class Languages extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * Get all language codes into array for check
     *
     * @return Collection
     */
    public static function readCode(): Collection
    {
        return Languages::select('code')
            ->get();
    }
}
