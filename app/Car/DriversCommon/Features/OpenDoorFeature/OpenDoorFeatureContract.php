<?php

namespace App\Car\DriversCommon\Features\OpenDoorFeature;

use App\Car\DriversCommon\Features\BaseFeature\BaseFeatureContract;

interface OpenDoorFeatureContract extends BaseFeatureContract
{
    public const TITLE = 'Удаленное открытие двери';

    public const DESCRIPTION = 'Позволяет удаленно получить доступ к машине и например открыть дверь';

    public function execute(OpenDoorInputDto $dto): OpenDoorOutputDto;
}
