<?php


namespace Hestia\MqttGateway\Command;


use Hestia\MqttGateway\Events;
use Hestia\MqttGateway\MQTT\Client;
use Illuminate\Console\Command;
use React\EventLoop\LoopInterface;

class MqttRunCommand extends Command
{
    protected $signature = 'mqtt:run';

    protected $description = 'Running mqtt process';

    public function handle(Client $client, LoopInterface $loop)
    {
        event(Events::STARTUP);
        $loop->addSignal(SIGTERM, function () use($client, $loop){
            try{
                event(Events::SHUTDOWN);
                $loop->stop();
            }catch (\Exception $exception){

            }
        });
        event(Events::CONNECT);
        $loop->run();
    }
}
