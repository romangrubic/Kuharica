<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\CategoriesTranslation;
use App\Models\Ingredients;
use App\Models\IngredientsTranslation;
use App\Models\Languages;
use App\Models\Meals;
use App\Models\MealsIngredients;
use App\Models\MealsTags;
use App\Models\TagsTranslation;
use Illuminate\Http\Request;
use App\Models\Tags as Tags;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Traits\Relationship;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

class TestController extends Controller
{
    /**
     * The meals repository implementation.
     *
     * @var Meals
     */
    protected $meals;

//    Instance of request
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @param  Meals  $meals
     * @return void
     */
    public function __construct(Meals $meals, Request $request)
    {
        $this->meals = $meals;
        $this->request = $request;
    }

    public function index()
    {
//        Validate function for language, default 'en'
        $this->validateLanguage($this->request->input('lang'));

//        Getting parameters from Request GET
//        "lang" is already set and "with" is not going to Meals
//        per_page has to be numeric (one number)
        $per_page = $this->validatePerPage((int)$this->request->input('per_page'));

//        Same with page
        $page = $this->validatePage((int)$this->request->input('page'));

//        Category can only be NULL, !NULL or numeric
        $category = $this->validateCategory(strtoupper($this->request->input('category')));

//        Check that tag array contains only numbers and removes string from it!
        $tags = $this->validateTags($this->request->input('tags'));

//        Diff time greater than 0
        $diff_time = $this->validateDiffTime($this->request->input('diff_time'));

//        Populating $parameters array
        $parameters = array_filter([
            'per_page' => $per_page,
            'page' => $page,
            'category' => $category,
            'tags' => $tags,
            'diff_time' => $diff_time,
        ]);

//        Getting data from the query
        $data = $this->meals::readMeals($parameters);

//        Formatting data
        $data = $this->formatData($data[0], $data[1]);

//        Calling method to format correct url routes for prev, next and self
        $data = $this->fullRoute($data, $this->request->all());

//        Check if there is 'with' in url GET
//        If with is null, finish everything and return response to User
        if ($this->request->input('with') == null){
            return response()->json($data);
        }
//        Else, calling appendWith() method to append users 'with' input to data and return response
        $this->appendWith($this->request->input('with'), $data);

        return response()->json($data);
    }

//    Formatting data to look the same as in task description
    private function formatData($meals, $countMeals)
    {
        $totalPages = 0;
        if ($countMeals != 0 ) {
            $totalPages = ceil($countMeals/$meals['per_page']);
        }

//        Formatting meta part
        $meta = [
            'currentPage' => $meals['current_page'],
            'totalItems' => $countMeals,
            'itemsPerPage' => (int)$meals['per_page'],
            'totalPages' => $totalPages
        ];

//        Formatting data part
        $data = $meals['data'];

//        Formatting links part
        $links = [
            'prev' => $meals['prev_page_url'],
            'next' => $meals['next_page_url'],
            'self' => $meals['path']
        ];

//        Returning formatted data
        return [
            'meta' => $meta,
            'data' => $data,
            'links' => $links
        ];
    }

//    Method that creates full length url with get params
    private function fullRoute($data, $getParams)
    {
        $route = '';
        foreach ($getParams as $key => $value) {
            if  ($key == 'page') {
                continue;
            }
            $route .= "&".$key.'='.$value;
        }

        if (isset($data['links']['prev'])) {
            $data['links']['prev'] .= $route;
        }
        if (isset($data['links']['next'])) {
            $data['links']['next'] .= $route;
        }
        $data['links']['self'] .= '?page=' . $this->request->input('page') . $route;

        return $data;
    }

//    Validating lang from GET param
    private function validateLanguage($language)
    {
//        Get all languages from DB to check if GET['lang'] exists or not!
        $codes = Languages::readCode();
//        Putting code values in array codeList to perform check
        $codeList = [];
        foreach ($codes as $code) {
            $codeList[] = $code->code;
        }
//        Check if $lang in array. If not, default to 'en' -> English
        if (!in_array($language, $codeList)) {
            $language = 'en';
        }
//        Setting locale to the $lang
        App::setLocale($language);
    }

//    per_page validation. Number greater than 0
    private function validatePerPage($input)
    {
        if ($input == null || $input == 0) {
            return null;
        }
        return $input;
    }

//    page validation. Number greater than 0
    private function validatePage($input)
    {
        if ($input == null || $input == 0) {
            return null;
        }
        return $input;

    }

//    category validation. Can be 'NULL', '!NULL' and number (id)
    private function validateCategory($input)
    {
        if ($input == 'NULL' or $input == '!NULL' or is_numeric($input) ) {
            return $input;
        } else {
            return null;
        }
    }

//    tags validation. Takes whole input and takes only numbers. Strings are omitted.
    private function validateTags($input)
    {
        $tags = array_filter(explode(',',$input));
        foreach ($tags as $tag) {
            if ((int)$tag == 0) {
                if (($key = array_search($tag, $tags)) !== false) {
                    unset($tags[$key]);
                }
            }
        }
        return $tags;
    }

//    diff_time has to be greater than 0
    private function validateDiffTime($input)
    {
        if ($input < 0) {
            return null;
        }
        return $input;
    }

//    Takes with parameter and appends desired data
    private function appendWith($with, $data)
    {
        $withList = explode(',', $with);
//        If 'with' array contains 'category'
        if (in_array('category', $withList)) {
            foreach ($data['data'] as $meal) {
                if ($meal->category_id != null) {
                    $category = Categories::getCategory($meal->category_id);
                    $object = CategoriesTranslation::getTitle($category->id);
                    $category->title = $object->title;
                    unset($meal->category_id);
                    $meal->category = $category;
                }
            };
        }
//        If 'with' array contains 'tags'
        if (in_array('tags', $withList)) {
            foreach ($data['data'] as $meal) {
                $tags = MealsTags::searchByMealId($meal->id);
                $tagList = [];
                foreach ($tags as $tag) {
                    $t = Tags::getTag($tag->tags_id);
                    $object = TagsTranslation::getTitle($t->id);
                    $t->title = $object->title;
                    $tagList[] = $t;
                }
                $meal->tags = $tagList;
            };
        }
//        If 'with' array contains 'ingredients'
        if (in_array('ingredients', $withList)) {
            foreach ($data['data'] as $meal) {
                $ingredients = MealsIngredients::searchByMealId($meal->id);
                $ingredientsList = [];
                foreach ($ingredients as $ingredient) {
                    $i = Ingredients::getIngredient($ingredient->ingredients_id);
                    $object = IngredientsTranslation::getTitle($i->id);
                    $i->title = $object->title;
                    $ingredientsList[] = $i;
                }
                $meal->ingredients = $ingredientsList;
            };
        }
    }
}
