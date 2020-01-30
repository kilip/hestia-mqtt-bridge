<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\MQTT\Server;
use Illuminate\Console\Command;

class MqttStopCommand extends Command
{
    protected $signature = 'mqtt:stop {--pid-file=}';

    protected $description = 'Stop MQTT Client Service';

    public function handle()
    {
        $pidFile = $this->option('pid-file');

        $process = new Server($pidFile);
        $process->stop();
    }
}
