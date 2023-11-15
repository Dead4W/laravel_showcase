<?php

namespace App\Car\DriversCommon\Features\OpenDoorFeature;

use App\Car\DriversCommon\Contracts\BaseInputDto;

class OpenDoorInputDto extends BaseInputDto
{

    public int $doorNumber;

}
