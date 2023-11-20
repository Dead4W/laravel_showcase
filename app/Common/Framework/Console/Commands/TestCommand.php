<?php

namespace App\Common\Framework\Console\Commands;

use App\Common\Http\Resources\ResponseResource;
use Illuminate\Console\Command;

class TestCommand extends Command
{

    protected $name = 'tst';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $resource = new ResponseResource(
            [
                'test' => 123,
            ]
        );

        dd(
            $resource->toArray()
        );
    }

}
