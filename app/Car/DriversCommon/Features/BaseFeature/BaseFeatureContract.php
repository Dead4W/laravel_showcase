<?php

namespace App\Car\DriversCommon\Features\BaseFeature;

use App\Car\DriversCommon\Contracts\CarDriverContract;

interface BaseFeatureContract
{
    public function __construct(
        CarDriverContract $carDriver,
    );

    public const TITLE = 'Название фичи';

    public const DESCRIPTION = 'Описание фичи';
}
