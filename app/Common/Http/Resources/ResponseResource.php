<?php

namespace App\Common\Http\Resources;

use App\Common\DeepJsonResource\DeepJsonResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ResponseResource extends DeepJsonResource
{
    protected function getAllowFields(): array
    {
        return self::ALL_FIELDS;
    }

    public function toArray(Request $request)
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            return new PageResource($this->resource, $this->castsMap);
        }

        return [
            'data' => $this->transformer->valueToSimpleType($this->resource, $this->castsMap),
        ];
    }

}
