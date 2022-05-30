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
    public function toArray($request): array
    {
        $meal = [
            'id' => $this->resource['id'],
            'title' => $this->resource['meals_translations'][0]['title'],
            'description' => $this->resource['meals_translations'][0]['description'],
            'status' => $this->helperStatus($request['diff_time']),
            'category' => $this->helperCategory($request['with']),
        ];

        $tags = $this->helperTagAndIngredient($request, 'tags');
        if ($tags) {
            $meal['tags'] = $tags;
        }

        $ingredients = $this->helperTagAndIngredient($request, 'ingredients');
        if ($ingredients) {
            $meal['ingredients'] = $ingredients;
        }

        return $meal;
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

//        $getTitle = $this->resource['category']->toArray();
//        dd($getTitle['categories_translations'][0]['title']);
        return [
            'id' => $this->resource['category']['id'],
            'title' => $this->resource['category']->toArray()['categories_translations'][0]['title'],
            'slug' => $this->resource['category']['slug']
        ];
    }

    private function helperTagAndIngredient($request, string $key)
    {
        if (in_array($key, explode(',', $request->input('with')))) {
            $keysList = [];
            $keys = [];
            foreach ($this->resource[$key] as $item) {
                $keys['id'] = $item->id;
                $keys['title'] = $item->toArray()[$key . '_translations'][0]['title'];
                $keys['slug'] = $item->slug;

                $keysList[] = $keys;
                $keys = [];
            }
            return $keysList;
        };
        return false;
    }
}
