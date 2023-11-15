<?php

namespace App\Common\Traits;

use LogicException;

trait MustInitializeTrait
{
    private bool $isInitialized = false;

    public function __call($method, $args)
    {
        if ($method === 'init') {
            $this->markInitialized();
        } else {
            $this->checkInit();
        }
    }

    protected function checkInit()
    {
        if (!$this->isInitialized) {
            throw new LogicException('DriverDefinition must be initialized');
        }
    }

    private function markInitialized(): void
    {
        $this->isInitialized = true;
    }
}
