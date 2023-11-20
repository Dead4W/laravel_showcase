<?php

namespace Tests\Unit\DeepJsonResourceTest;

use App\Common\DeepJsonResource\DeepJsonResource;

class TestResourceAllProperties extends DeepJsonResource
{

    protected function getAllowFields(): array
    {
        return self::ALL_FIELDS;
    }
}
