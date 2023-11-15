<?php

namespace App\Car\Resources;

class CarListItemResource extends CarResource
{

    protected function getAllowFields(): array
    {
        return array_merge(
            parent::getAllowFields(),
            [
                'is_busy', 'features',
            ]
        );
    }
}
