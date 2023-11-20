<?php

namespace App\Car\Resources;

use App\Common\DeepJsonResource\DeepJsonResource;

class CarResource extends DeepJsonResource
{

    protected function getAllowFields(): array
    {
        return ['uuid', 'company', 'model_family', 'model_number'];
    }
}
