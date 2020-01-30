<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\MQTT\Server;
use Illuminate\Console\Command;

class MqttStopCommand extends Command
{
    protected $signature = 'mqtt:stop';

    protected $description = 'Stop MQTT Client Service';

    public function handle()
    {
        $process = new Server();
        $process->stop();
    }
}
