<?php

namespace App\Car\Drivers;

use App\Car\DriversCommon\BaseCarDriver;
use App\Car\DriversCommon\Contracts\CarDriverContract;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorFeatureContract;

class ExampleCarDriver extends BaseCarDriver implements CarDriverContract
{

    static function getFeatures(): array
    {
        return [
            OpenDoorFeatureContract::class => Features\OpenDoorFeature::class,
        ];
    }
}
