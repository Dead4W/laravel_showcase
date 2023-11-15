<?php

namespace App\Car\Controllers;

use App\Car\DriversCommon\CarDriverFactory;
use App\Car\DriversCommon\Features\BaseFeature\BaseFeatureContract;
use App\Car\Models\Car;
use App\Car\Resources\CarListItemResource;
use App\Common\Http\Controllers\Controller;
use App\Common\Http\Requests\PageRequest;
use App\Common\Http\Resources\ResponseResource;
use Illuminate\Pagination\AbstractPaginator;

class CarController extends Controller
{

    public function list(PageRequest $request): ResponseResource {
        /** @var AbstractPaginator $result */
        $result = Car::query()
            ->paginate(
                perPage: $request->validated('limit'),
                page: $request->validated('page'),
            );

        $result
            ->each(function (Car $car) {
                $car->features = CarDriverFactory::getFeaturesByFamilyId($car->family_id)
                    ->keys()
                    ->map(function ($feature) {
                        /** @var BaseFeatureContract $feature */
                        return [
                            'name' => class_basename($feature),
                            'title' => $feature::TITLE,
                            'description' => $feature::DESCRIPTION,
                        ];
                    });
            });

        return new ResponseResource($result, [
            Car::class => CarListItemResource::class,
        ]);
    }

}
