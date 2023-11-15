<?php

namespace App\Car\DriversCommon;

use App\Car\DriversCommon\Exceptions\FeatureNotFoundException;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorFeatureContract;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorInputDto;
use App\Car\Models\Car;

class CarDriverOrchestrator
{

    public function openDoors(Car $car, array $doorNumbers): bool {
        $driver = CarDriverFactory::getDriverByCar($car);

        // Open many doors for single execute
        if ($driver->isAvailableFeature(OpenManyDoorsFeatureContract::class)) {
            $feature = $driver->getFeatureOrError(OpenManyDoorsFeatureContract::class);

            $inputDto = new OpenDoorInputDto();
            $inputDto->doorNumbers = $doorNumbers;

            return $feature->execute($inputDto)->result;
        }

        // open doors for loop with multiple execute
        if ($driver->isAvailableFeature(OpenDoorFeatureContract::class)) {
            $feature = $driver->getFeatureOrError(OpenDoorFeatureContract::class);

            $result = true;
            foreach ($doorNumbers as $doorNumber) {
                $inputDto = new OpenDoorInputDto();
                $inputDto->doorNumber = $doorNumber;

                $result = $feature->execute($inputDto)->result && $result;
            }

            return $result;
        }

        throw new FeatureNotFoundException('Feature for open door not found!');
    }
}
