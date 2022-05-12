<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealsTags extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals_tags';
    use HasFactory;
}
