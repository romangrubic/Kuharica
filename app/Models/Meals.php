<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Meals extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meals';

    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['slug'];

    public static function readMeals($parameters)
    {
//        Get all meals for testing, later add the parameters
        $meals = DB::table('meals')
            ->select('id', 'title', 'description', 'status', 'category_id')
            ->paginate(2)
            ->toArray();

//        Get translations and put them in their place
        foreach ($meals['data'] as $meal) {
            $translation = MealsTranslation::getTitleAndDescription($meal->id);
            $meal->title = $translation->title;
            $meal->description = $translation->description;
        }

//        Count for meta['totalItems']
        $countMeals = DB::table('meals')->count();

//        dd($meals) is showing me tha data I need to mimic the given response from the Task;

//        Formatting meta part
        $meta = [
            'currentPage' => $meals['current_page'],
            'totalItems' => $countMeals,
            'itemsPerPage' => $meals['per_page'],
            'totalPages' => $meals['total']
        ];

//        Formatting data part
        $data = $meals['data'];

//        Formatting links part
        $links = [
            'prev' => $meals['prev_page_url'],
            'next' => $meals['next_page_url'],
            'self' => $meals['path']
        ];

//        Returning response to controller
        return [
            'meta' => $meta,
            'data' => $data,
            'links' => $links
        ];
    }
}
