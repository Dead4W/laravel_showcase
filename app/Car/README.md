Архитектура драйверов дает большую гибкость разработчику и разделяет бизнес логику от более технической.

Вот пример использования
```php
$carDriverOrchestrator = resolve(CarDriverOrchestrator::class);

$car = Car::query()->first();
$doorNumbers = [0, 1, 2, 3];

$carDriverOrchestrator->openDoors($car, $doorNumbers);
```

---

Драйвер создается следующим образом
```php
class ExampleCarDriver extends BaseCarDriver implements CarDriverContract
{

    static function getFeatures(): array
    {
        return [
            OpenDoorFeatureContract::class => Features\OpenDoorFeature::class,
        ];
    }
}
```

---

В вызове  `DriverOrchestrator->openDoors(Car $car, array $doorNumbers)` будет такая логика
```php
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
```
