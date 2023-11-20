<?php

namespace App\User\Resources;

use App\Common\DeepJsonResource\DeepJsonResource;

class UserResource extends DeepJsonResource
{

    protected function getAllowFields(): array
    {
        return ['id', 'name', 'email'];
    }
}
