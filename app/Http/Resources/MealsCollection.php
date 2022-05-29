<?php

namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MealsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  MealsGetRequest  $request
     * @return array
     */
    public function toArray($request)
    {

//        dd($this->resource->toArray());
//        dd($request->input());
        return [
            'data' => $this->collection,
            ];
    }
}
