<?php

namespace App\User\Resources;

use App\Common\Http\Resources\BaseJsonResource;

class UserResource extends BaseJsonResource
{

    protected function getAllowFields(): array
    {
        return ['id', 'name', 'email'];
    }
}
