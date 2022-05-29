<?php

namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class MealsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  MealsGetRequest  $request
     * @return array
     */
    public function toArray($request)
    {
//        return $this->resource;
        return [
            'id' => $this->resource['id'],
            'title' => $this->resource['meals_translations'][0]['title'],
            'description' => $this->resource['meals_translations'][0]['description'],
            'status' => $this->helperStatus($request['diff_time']),
            'category' => $this->helperCategory($request['with'])
            ];
    }

    private function helperStatus($param): string
    {
        if (!isset($param)) {
            return 'created';
        }

        if ($this->resource['deleted_at'] != null) {
            return 'deleted';
        }

        if ($this->resource['created_at'] == $this->resource['updated_at']) {
            return 'created';
        } else {
            return 'modified';
        }
    }

    private function helperCategory($param)
    {
        if (!in_array('category', explode(',', $param))) {
            return $this->resource['category_id'];
        }

        if ($this->resource['category'] == null) {
            return $this->resource['category_id'];
        }

        return [
            'id' => $this->resource['category']['id'],
            'title' => $this->resource['category']['categories_translations'][0]['title'],
            'slug' => $this->resource['category']['slug']
        ];
    }
}
