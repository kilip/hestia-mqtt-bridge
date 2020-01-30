<?php


namespace Hestia\MqttGateway;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct($basePath = null)
    {
        parent::__construct(realpath(__DIR__.'/../'));
    }
}
