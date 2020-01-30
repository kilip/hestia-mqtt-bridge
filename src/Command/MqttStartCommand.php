<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\MQTT\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MqttStartCommand extends Command
{
    protected $signature = 'mqtt:start {--pid-file=}';

    public function handle()
    {
        if(!\extension_loaded('pcntl')){
            throw new \Exception('PHP Extension pcntl is not loaded');
        }

        $pidFile = $this->option('pid-file');
        try{
            $server = new Server($pidFile);
            $server->start();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return 100;
        }
    }
}
