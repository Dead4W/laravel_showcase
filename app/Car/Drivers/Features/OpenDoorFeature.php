<?php

namespace App\Car\Drivers\Features;

use App\Car\DriversCommon\Features\BaseFeature\BaseFeature;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorFeatureContract;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorInputDto;
use App\Car\DriversCommon\Features\OpenDoorFeature\OpenDoorOutputDto;

class OpenDoorFeature extends BaseFeature implements OpenDoorFeatureContract
{

    public function execute(OpenDoorInputDto $dto): OpenDoorOutputDto
    {
        // open door for $dto->doorNumber

        $resultDto = new OpenDoorOutputDto();
        $resultDto->result = true;
        return $resultDto;
    }
}
