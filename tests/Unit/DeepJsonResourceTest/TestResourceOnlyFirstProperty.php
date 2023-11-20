<?php

namespace Tests\Unit\DeepJsonResourceTest;

use App\Common\DeepJsonResource\DeepJsonResource;

class TestResourceOnlyFirstProperty extends DeepJsonResource
{

    protected function getAllowFields(): array
    {
        return ['property1'];
    }
}
