<?php

namespace App\Common\Http\Resources;

use Illuminate\Http\Request;

class PageResource extends BaseJsonResource
{
    protected function getAllowFields(): array
    {
        return ['*'];
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
            'data' => $this->valueToSimpleType($this->resource->items()),
        ];
    }
}
