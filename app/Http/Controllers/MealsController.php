<?php

/**
 * This file contains controller for /api/meals (Meals model).
 */

namespace App\Http\Controllers;

use App\Http\{Requests\MealsGetRequest,
    Resources\MealsCollection};
use App\Models\Meals;
use Illuminate\Http\JsonResponse;

/**
 * MealsController is a controller class for Meals model.
 */

class MealsController extends Controller
{
    /**
     * Properties for dependency injection and their types
     */
    protected Meals $meals;
    protected MealsGetRequest $request;

    /**
     * Create a new controller instance with dependencies.
     *
     * @param  Meals  $meals
     * @param MealsGetRequest $request
     * @return void
     */
    public function __construct(Meals $meals, MealsGetRequest $request)
    {
        $this->meals = $meals;
        $this->request = $request;
    }

    /**
     * index() is main method for MealsController class
     *
     * Form request (MealsGetRequests.php) is validating the user input (GET parameters)
     * Query (Meals::readMeals in Models\Meals.php) is sent with those parameters
     * Result is passed to MealsCollection (MealsCollection.php and MealsResource.php) for formatting response
     * Display a list of meals with requested parameters in Json for user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(new MealsCollection($this->meals::readMeals($this->request->validated())));

        /**
         *  Code below does the sam job as above, but is more readable
         *
         *  $parameters = $this->request->validated();
         *
         *  $data = $this->meals::readMeals($parameters);
         *
         *  return response()->json(new MealsCollection($data));
         */
    }
}
