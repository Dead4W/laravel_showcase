<?php

namespace App\Car\DriversCommon;

use App\Car\Drivers\ExampleCarDriver;
use App\Car\DriversCommon\Contracts\CarDriverContract;
use App\Car\Models\Car;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CarDriverFactory
{
    public const CAR_DRIVER_EXAMPLE = 1;

    public const CAR_DRIVER_MAP = [
        self::CAR_DRIVER_EXAMPLE => ExampleCarDriver::class,
    ];

    public static function getDriverByCar(Car $car): CarDriverContract {
        $familyId = $car->family_id;

        $driverPath = self::CAR_DRIVER_MAP[$familyId] ?? null;

        if ($driverPath === null) {
            throw new InvalidArgumentException("Invalid driver family #{$familyId}");
        }

        /** @var CarDriverContract $driverInstance */
        $driverInstance = resolve($driverPath);
        $driverInstance->init($car);

        return $driverInstance;
    }

    public static function getFeaturesByFamilyId(int $familyId): Collection
    {
        /** @var CarDriverContract $driverPath */
        $driverPath = self::CAR_DRIVER_MAP[$familyId] ?? null;

        if ($driverPath === null) {
            throw new InvalidArgumentException("Invalid driver family #{$familyId}");
        }

        return collect($driverPath::getFeatures());
    }

}
