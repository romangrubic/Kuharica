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

class TestController extends Controller
{
    public function index(Request $request)
    {
//        Validate function for language, default 'en'
        $this->validateLanguage($request->input('lang'));

//        Getting parameters from Request GET only if they are not null
//        "lang" is already set and "with" is not going to Meals
//        per_page has to be numeric (one number)
        $per_page = (int)$request->input('per_page');
        if ($per_page == null || $per_page == 0) {
            $per_page = null;
        }

//        Same with page
        $page = (int)$request->input('page');
        if ($page == null || $page == 0) {
            $page = null; // or one
        }

//        Category can only be NULL, !NULL or numeric
        $category = strtoupper($request->input('category'));
        if ($category == 'NULL' or $category == '!NULL' or is_numeric($category) ) {
            $category = strtoupper($request->input('category'));
        } else {
            $category = null;
        }

//        Check that tag array contains only numbers and removes string from it!
        $tags = array_filter(explode(',',$request->input('tags')));
        foreach ($tags as $tag) {
            if ((int)$tag == 0) {
                if (($key = array_search($tag, $tags)) !== false) {
                    unset($tags[$key]);
                }
            }
        }

        $diff_time = $request->input('diff_time');

        $parameters = array_filter([
            'per_page' => $per_page,
            'page' => $page,
            'category' => $category,
            'tags' => $tags,
            'diff_time' => $diff_time,
        ]);

        $data = Meals::readMeals($parameters);


//        Check if there is 'with' in url GET
//        If with is null, finish everything and return response to User
        if ($request->input('with') == null){
            return response()->json($data);
        } else {
//        Else, calling appendWith() method to append users 'with' input to data
            $this->appendWith($request->input('with'), $data);
        };

//        Return response.
        return response()->json($data);
    }

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
