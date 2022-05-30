<?php

namespace App\Http\Resources;

use App\Http\Requests\MealsGetRequest;
use Illuminate\Http\Request;
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
        return [
            'meta' => $this->formatMeta($this->resource->toArray()),
            'data' => $this->collection,
            'links' => $this->formatLinks($this->resource->toArray(), $request),
            ];
    }

    /**
     * Format meta data
     *
     * @param array $resource
     * @return array
     */
    private function formatMeta(array $resource): array
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
     * Format link data
     *
     * @param array $resource
     * @param Request $request
     * @return array
     */
    private function formatLinks(array $resource, Request $request): array
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
