<?php

/**
 * This file contains formatting instructions for /api/meals route data.
 */
namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use App\Http\Resources\ResourcesHelpers\MealsCollectionHelpers;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * MealsCollection is a class for formatting meals data.
 */
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
        return [
            'meta' => MealsCollectionHelpers::formatMeta($this->resource->toArray()),
            'data' => $this->collection,
            'links' => MealsCollectionHelpers::formatLinks($this->resource->toArray(), $request),
            ];
    }
}
