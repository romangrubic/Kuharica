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
//        $meals = Meals::query();
//        $meals->select('id', 'title', 'description', 'status', 'category_id');
//
////            ->where('category_id', '=', (isset($parameters['category'])))
//           $meals->paginate(((isset($parameters['per_page']))?$parameters['per_page']:10),'[*]','page',((isset($parameters['page']))?$parameters['page']:1) );


//        if (isset($parameters['per_page']) && is_int($parameters['per_page']) ) {
//            $meals->paginate($parameters['per_page'])->toArray();
//        }else{
//            $meals->paginate(10)->toArray();
//        };
//            $meals->get()->toArray();

        $query = DB::table('meals')->select('id', 'title', 'description', 'status', 'category_id');
//        if ($parameters['category']) {
//            $query->where('category_id', $parameters['category']);
//        };

        $query->paginate(((isset($parameters['per_page']))?$parameters['per_page']:10),'[*]','page',((isset($parameters['page']))?$parameters['page']:1))
        ->toArray();

//        if ($parameters['category'] != null) {
//            switch ($parameters['category']) {
//                case('NULL'):
//                    $meals->where('category_id', 'is', null);
//                    break;
//                case('!NULL'):
//                    $meals->where('category_id', 'is not', null);
//                    break;
//                default:
//                    $meals->where('category_id', '=', $parameters['category']);
//                    break;
//            }
//        }
//        $categoryOperator = '';
//        switch ($parameters['category']) {
//                case('NULL'):
//                    $categoryOperator = 'is ';
//                    $parameters['category'] = null;
//                    break;
//                case('!NULL'):
//                    $categoryOperator = 'is not';
//                    $parameters['category'] = !null;
//                    break;
//                default:
//                    $categoryOperator = '=';
//                    break;
//            }
        $meals = DB::table('meals')
            ->select('id', 'title', 'description', 'status', 'category_id')
            ->where(function ($query) use ($parameters) {
                if (!isset($parameters['category'])){
                    return;
                }
                switch ($parameters['category']) {
                case('NULL'):
                    $query->whereNull('category_id');
                    break;
                case('!NULL'):
                    $query->whereNotNull('category_id');
                    break;
                default:
                    $query->where('category_id', '=', $parameters['category']);
                    break;
                }
            })
            ->paginate(((isset($parameters['per_page']))?$parameters['per_page']:10),'[*]','page',((isset($parameters['page']))?$parameters['page']:1))
            ->toArray();

//        dd($meals);
//        Get translations and put them in their place
        foreach ($meals['data'] as $meal) {
            $translation = MealsTranslation::getTitleAndDescription($meal->id);
            $meal->title = $translation->title;
            $meal->description = $translation->description;
        }

//        Count for meta['totalItems']
        $countMeals = count($meals);

//        dd($meals) is showing me tha data I need to mimic the given response from the Task;

//        Formatting meta part
        $meta = [
            'currentPage' => $meals['current_page'],
            'totalItems' => 'Needs refactoring',
            'itemsPerPage' => (int)$meals['per_page'],
            'totalPages' => ceil($countMeals/$meals['per_page'])
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
