<?php

namespace App\Car\DriversCommon\Contracts;

use App\Car\DriversCommon\Exceptions\FeatureNotFoundException;
use App\Car\DriversCommon\Features\BaseFeature\BaseFeatureContract;
use App\Car\Models\Car;

interface CarDriverContract
{

    public function init(
        Car $car,
    );

    /**
     * Check feature is available in car
     * @param string $feature path to BaseFeatureContract class
     * @return bool
     */
    public function isAvailableFeature(string $feature): bool;

    public function getCar(): Car;

    /**
     * @throws FeatureNotFoundException
     */
    public function getFeatureOrError(string $feature): BaseFeatureContract;

    /**
     * @return array<string, string>
     */
    static function getFeatures(): array;
}
