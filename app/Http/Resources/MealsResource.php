<?php

namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use Illuminate\Http\Request;
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

        foreach (['tags', 'ingredients'] as $item) {
            $data = $this->helperWithData($request, $item);
            if ($data) {
                $meal[$item] = $data;
            }
        }

        return $meal;
    }

    /**
     * Sets 'status' key with correct status type (created|modified|deleted)
     *
     * @param string $param
     * @return string
     */
    private function helperStatus(string $param): string
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

    /**
     * Sets 'category' key with category data
     *
     * @param string $param
     * @return array|mixed
     * if I write type array|mixed next to function, it throws a syntax error because of |
     */
    private function helperCategory(string $param)
    {
        if (!in_array('category', explode(',', $param))) {
            return $this->resource['category_id'];
        }

        if ($this->resource['category'] == null) {
            return $this->resource['category_id'];
        }

        return [
            'id' => $this->resource['category']['id'],
            'title' => $this->resource['category']->toArray()['categories_translations'][0]['title'],
            'slug' => $this->resource['category']['slug']
        ];
    }

    /**
     * Sets keys 'tags' and 'ingredients' if they are set in GET 'with' parameter
     *
     * @param Request $request
     * @param string $key
     * @return array|bool
     * if I write type array|mixed next to function, it throws a syntax error because of |
     */
    private function helperWithData(Request $request, string $key)
    {
        if (in_array($key, explode(',', $request->input('with')))) {
            $dataList = [];
            foreach ($this->resource[$key] as $item) {
                $data['id'] = $item->id;
                $data['title'] = $item->toArray()[$key . '_translations'][0]['title'];
                $data['slug'] = $item->slug;

                $dataList[] = $data;
            }
            return $dataList;
        };
        return false;
    }
}
