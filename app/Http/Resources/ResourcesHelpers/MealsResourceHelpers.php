<?php

/**
 * This file contains helper methods for MealsResource.
 * Keeping them in MealsResource would violate SOLID (to my understanding of it)
 * so I made a MealsResourceHelpers class for them.
 */

namespace App\Http\Resources\ResourcesHelpers;

use App\Models\Meals;
use Illuminate\Http\Request;

/**
 * MealsResourceHelpers class contains helper methods for MealsResource.
 */
class MealsResourceHelpers
{
    /**
     * Sets 'status' key with correct status type (created|modified|deleted)
     *
     * @param string|null $param
     * @param Meals $resource
     * @return string
     */
    public static function helperStatus($param, Meals $resource): string
    {
        if (!isset($param)) {
            return 'created';
        }

        if ($resource['deleted_at'] != null) {
            return 'deleted';
        }

        if ($resource['created_at'] == $resource['updated_at']) {
            return 'created';
        } else {
            return 'modified';
        }
    }

    /**
     * Sets 'category' key with category data
     *
     * @param string $param
     * @param Meals $resource
     * @return array|mixed
     * if I write type array|mixed next to function, it throws a syntax error because of |
     */
    public static function helperCategory(string $param, Meals $resource)
    {
        if (!in_array('category', explode(',', $param))) {
            return $resource['category_id'];
        }

        if ($resource['category'] == null) {
            return $resource['category_id'];
        }

        return [
            'id' => $resource['category']['id'],
            'title' => $resource['category']->toArray()['categories_translations'][0]['title'],
            'slug' => $resource['category']['slug']
        ];
    }

    /**
     * Sets keys 'tags' and 'ingredients' if they are set in GET 'with' parameter
     *
     * @param Request $request
     * @param string $key
     * @param Meals $resource
     * @return array|bool
     * if I write type array|mixed next to function, it throws a syntax error because of |
     */
    public static function helperWithData(Request $request, string $key, Meals $resource)
    {
        if (in_array($key, explode(',', $request->input('with')))) {
            $dataList = [];
            foreach ($resource[$key] as $item) {
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
