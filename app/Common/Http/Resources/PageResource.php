<?php

namespace App\Common\Http\Resources;

use App\Common\DeepJsonResource\DeepJsonResource;
use Illuminate\Http\Request;

class PageResource extends DeepJsonResource
{
    protected function getAllowFields(): array
    {
        return self::ALL_FIELDS;
    }

    public function toArray(Request $request)
    {
        return [
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'limit' => $this->resource->perPage(),
                'total' => $this->resource->total(),
            ],
            'data' => $this->transformer->valueToSimpleType($this->resource, $this->castsMap),
        ];
    }
}
