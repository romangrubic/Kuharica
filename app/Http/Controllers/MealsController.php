<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealsGetRequest;
use App\Http\Resources\MealsCollection;
use App\Http\Resources\MealsResource;
use App\Models\Meals;
use Illuminate\Http\JsonResponse;


class MealsController extends Controller
{
//    For dependency injection
    protected Meals $meals;
    protected MealsGetRequest $request;

    /**
     * Create a new controller instance.
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

//    Main method
    public function index(): JsonResponse
    {
        $parameters = $this->request->validated();

//        dd($this->meals::readMeals($parameters));
        $data = $this->meals::readMeals($parameters);
//        $newList = [];
//        dd($data);
//        foreach ($array['data'] as $o) {
//            foreach ($o as $k => $v) {
//                if ($k == 'id') {
//                    $newList[] = $v;
//                }
//            }
//        }
//        dd($newList);
//        $data = getData($parameters);
//        $data = $this->meals::whereIn('id', $newList)->get()->toArray();

//        if (isset($parameters['with']) && isset($parameters['diff_time'])) {
//            $withList = explode(',', $parameters['with']);
////            If I leave protected $with in meals model than it gets called twice instead of once
////            So I am adding it here
//            $withList[] = 'meals_translations';
//            $data = $this->meals::withTrashed()->with($withList)->whereIn('id', $newList)->get();
//        } elseif (isset($parameters['with'])) {
//            $withList = explode(',', $parameters['with']);
//            $withList[] = 'meals_translations';
//            $data = $this->meals::with($withList)->whereIn('id', $newList)->get();
//        } elseif (isset($parameters['diff_time'])) {
//            $data = $this->meals::withTrashed()->whereIn('id', $newList)->get();
//        } else {
//            $data = $this->meals::whereIn('id', $newList)->get();
//        };

//        Sending through MealsCollection (resource)
        return response()->json(new MealsCollection($data));

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
}
