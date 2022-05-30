<?php

/**
 * This file contains formatting instructions for data (meals items).
 */
namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use App\Http\Resources\ResourcesHelpers\MealsResourceHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class MealsResource extends JsonResource
{
    /**
     * Transform the resource into an array with specified keys.
     *
     * @param  MealsGetRequest  $request
     * @return array
     */
    public function toArray($request): array
    {
        $meal = [
            'id' => $this->resource['id'],
            'title' => $this->resource['meals_translations'][0]['title'],
            'description' => $this->resource['meals_translations'][0]['description'],
            'status' => MealsResourceHelpers::helperStatus($request['diff_time'], $this->resource),
            'category' => MealsResourceHelpers::helperCategory($request['with'], $this->resource),
        ];

        foreach (['tags', 'ingredients'] as $item) {
            $data = MealsResourceHelpers::helperWithData($request, $item, $this->resource);
            if ($data) {
                $meal[$item] = $data;
            }
        }

        return $meal;
    }
}
