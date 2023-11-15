<?php

namespace App\Car\Resources;

use App\Common\Http\Resources\BaseJsonResource;

class CarResource extends BaseJsonResource
{

    protected function getAllowFields(): array
    {
        return ['uuid', 'company', 'model_family', 'model_number'];
    }
}
