<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Languages extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    use HasFactory;

//    Getting code values from languages table
    public static function readCode()
    {
        return DB::table('languages')
            ->select('code')
            ->get()
            ->toArray();
    }
}
