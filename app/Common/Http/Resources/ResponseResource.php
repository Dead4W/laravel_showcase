<?php

namespace App\Common\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ResponseResource extends BaseJsonResource
{
    protected function getAllowFields(): array
    {
        return ['*'];
    }

    public function toArray(Request $request)
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            return new PageResource($this->resource, $this->customCastClassToResourceMap);
        }

        return [
            'data' => $this->valueToSimpleType($this->resource),
        ];
    }

}
