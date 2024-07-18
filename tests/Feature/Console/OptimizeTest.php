<?php

namespace Tests\Feature\Console;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class OptimizeTest extends TestCase
{
    public function testOptimizeSucceeds()
    {

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('config:clear');
            $this->artisan('route:clear');
        });

        $this->artisan('optimize')->assertSuccessful();
    }
}
