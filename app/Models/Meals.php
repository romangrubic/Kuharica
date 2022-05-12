<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meals extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals';

    use SoftDeletes;

    use HasFactory;
}
