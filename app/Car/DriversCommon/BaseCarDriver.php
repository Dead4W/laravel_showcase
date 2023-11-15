<?php

namespace App\Car\DriversCommon;

use App\Car\DriversCommon\Contracts\CarDriverContract;
use App\Car\DriversCommon\Exceptions\FeatureNotFoundException;
use App\Car\DriversCommon\Features\BaseFeature\BaseFeatureContract;
use App\Car\Models\Car;
use App\Common\Traits\MustInitializeTrait;
use Illuminate\Support\Collection;

abstract class BaseCarDriver implements CarDriverContract
{
    use MustInitializeTrait;

    protected Car $car;

    protected array $initFeatures = [];

    public function init(Car $car)
    {
        $this->car = $car;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function isAvailableFeature(string $feature): bool
    {
        return $this->getAllFeatures()->has($feature);
    }

    public function getFeatureOrError(string $feature): BaseFeatureContract
    {
        if (!$this->isAvailableFeature($feature)) {
            $car = $this->getCar();
            throw new FeatureNotFoundException("feature '$feature' is not available in current driver #{$car->family_id}");
        }

        if (!isset($this->initFeatures[$feature])) {
            $featurePath = $this->getAllFeatures()->get($feature);

            /** @var BaseFeatureContract $featureInstance */
            $this->initFeatures[$feature] = new $featurePath($this);
        }

        return $this->initFeatures[$feature];
    }

    /**
     * @return Collection<string, string> sample BaseFeatureContract::class => BaseFeature::class
     */
    protected function getAllFeatures(): Collection
    {
        return collect($this::getFeatures());
    }
}
