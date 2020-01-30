<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\MQTT\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MqttStartCommand extends Command
{
    protected $signature = 'mqtt:start';

    public function handle()
    {
        if(!\extension_loaded('pcntl')){
            throw new \Exception('PHP Extension pcntl is not loaded');
        }

        try{
            $server = new Server();
            $server->start();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return 100;
        }
    }
}
