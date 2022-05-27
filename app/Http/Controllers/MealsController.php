<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealsGetRequest;
use App\Models\Categories;
use App\Models\CategoriesTranslation;
use App\Models\Ingredients;
use App\Models\IngredientsTranslation;
use App\Models\Languages;
use App\Models\Meals;
use App\Models\MealsIngredients;
use App\Models\MealsTags;
use App\Models\Tags;
use App\Models\TagsTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class MealsController extends Controller
{

//    For dependency injection
    protected $meals;
    protected $request;
    protected $languages;
    protected $app;
    protected $categories;
    protected $categoriesTranslation;
    protected $mealsTags;
    protected $tags;
    protected $tagsTranslation;
    protected $mealsIngredients;
    protected $ingredients;
    protected $ingredientsTranslation;

    /**
     * Create a new controller instance.
     *
     * @param  Meals  $meals
     * @return void
     */
    public function __construct(Meals $meals,
                                MealsGetRequest $request,
                                Categories $categories,
                                CategoriesTranslation $categoriesTranslation,
                                MealsTags $mealsTags,
                                Tags $tags,
                                TagsTranslation $tagsTranslation,
                                MealsIngredients $mealsIngredients,
                                Ingredients $ingredients,
                                IngredientsTranslation $ingredientsTranslation)
    {
        $this->meals = $meals;
        $this->request = $request;
        $this->categories = $categories;
        $this->categoriesTranslation = $categoriesTranslation;
        $this->mealsTags = $mealsTags;
        $this->tags = $tags;
        $this->tagsTranslation = $tagsTranslation;
        $this->mealsIngredients = $mealsIngredients;
        $this->ingredients = $ingredients;
        $this->ingredientsTranslation = $ingredientsTranslation;
    }

//    Main method
    public function index(): JsonResponse
    {
        $validated = $this->request->validated();

        $parameters = [];
        foreach ($validated as $k => $v) {
            $parameters += [$k => $v];
        }
//        dd($parameters);


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
    private function formatData(array $meals, int $countMeals)
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

//    Takes with parameter and appends desired data
    private function appendWith($with, $data)
    {
        $withList = explode(',', $with);
//        If 'with' array contains 'category'
        if (in_array('category', $withList)) {
            foreach ($data['data'] as $meal) {
                if ($meal->category_id != null) {
                    $category = $this->categories::getCategory($meal->category_id);
                    $object = $this->categoriesTranslation::getTitle($category->id);
                    $category->title = $object->title;
                    unset($meal->category_id);
                    $meal->category = $category;
                }
            };
        }
//        If 'with' array contains 'tags'
        if (in_array('tags', $withList)) {
            foreach ($data['data'] as $meal) {
                $tags = $this->mealsTags::searchByMealId($meal->id);
                $tagList = [];
                foreach ($tags as $tag) {
                    $t = $this->tags::getTag($tag->tags_id);
                    $object = $this->tagsTranslation::getTitle($t->id);
                    $t->title = $object->title;
                    $tagList[] = $t;
                }
                $meal->tags = $tagList;
            };
        }
//        If 'with' array contains 'ingredients'
        if (in_array('ingredients', $withList)) {
            foreach ($data['data'] as $meal) {
                $ingredients = $this->mealsIngredients::searchByMealId($meal->id);
                $ingredientsList = [];
                foreach ($ingredients as $ingredient) {
                    $i = $this->ingredients::getIngredient($ingredient->ingredients_id);
                    $object = $this->ingredientsTranslation::getTitle($i->id);
                    $i->title = $object->title;
                    $ingredientsList[] = $i;
                }
                $meal->ingredients = $ingredientsList;
            };
        }
    }
}
