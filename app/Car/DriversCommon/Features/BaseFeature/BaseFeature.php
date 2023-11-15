<?php

namespace App\Car\DriversCommon\Features\BaseFeature;

use App\Car\DriversCommon\Contracts\CarDriverContract;
use App\Car\Models\Car;

abstract class BaseFeature implements BaseFeatureContract
{
    public function __construct(
        protected CarDriverContract $carDriver,
    ) {
    }

    protected function getDriverDefinition(): CarDriverContract
    {
        return $this->carDriver;
    }

    protected function getCar(): Car
    {
        return $this
            ->getDriverDefinition()
            ->getCar();
    }
}
