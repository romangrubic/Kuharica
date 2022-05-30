<?php

/**
 * This file contains helper methods for MealsCollection.
 * Keeping them in MealsCollection would violate SOLID (to my understanding of it)
 * so I made a MealsCollectionHelpers class for them.
 */

namespace App\Http\Resources\ResourcesHelpers;

use Illuminate\Http\Request;

/**
 * MealsCollectionHelpers class contains helper methods for MealsCollection.
 */
class MealsCollectionHelpers
{
    /**
     * Format meta data for MealsCollection
     *
     * @param array $resource
     * @return array
     */
    public static function formatMeta(array $resource): array
    {
        $totalPages = ceil($resource['total']/(int) $resource['per_page']);

        return [
            'current_page' => $resource['current_page'],
            'totalItems' => $resource['total'],
            'itemsPerPage' => (int) $resource['per_page'],
            'totalPages' => $totalPages,
        ];
    }

    /**
     * Format link data for MealsCollection
     *
     * @param array $resource
     * @param Request $request
     * @return array
     */
    public static function formatLinks(array $resource, Request $request): array
    {
        $route = '';
        foreach ($request->input() as $key => $value) {
            if  ($key == 'page') {
                continue;
            }
            $route .= "&".$key.'='.$value;
        }

        foreach (['prev_page_url', 'next_page_url'] as $item) {
            if ($resource[$item] == null) {
                $url[$item] = null;
            } else {
                $url[$item] = $resource[$item] . $route;
            }
        }

        return [
            'prev' => $url['prev_page_url'],
            'next' => $url['next_page_url'],
            'self' => $resource['path'] . '?page=' . $resource['current_page'] . $route
        ];
    }
}
