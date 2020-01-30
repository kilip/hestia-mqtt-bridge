<?php


namespace Test\Hestia\MqttGateway;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../etc/sandbox/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;

    }
}
