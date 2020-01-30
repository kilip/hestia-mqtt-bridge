<?php

namespace Hestia\MqttGateway\MQTT;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\Connection;
use BinSoul\Net\Mqtt\DefaultSubscription;
use BinSoul\Net\Mqtt\Message;
use Hestia\MqttGateway\Events;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use React\EventLoop\LoopInterface;

class Client
{
    /**
     * @var ReactMqttClient
     */
    private $client;

    /**
     * @var Connection
     */
    private $connection;

    private $shutdown = false;

    /**
     * @var LoopInterface
     */
    private $loop;

    public function __construct(
        ReactMqttClient $client,
        LoopInterface $loop,
        Connection $connection
    )
    {
        $this->client = $client;
        $this->connection = $connection;
        $this->loop = $loop;

        $this->configureClient();
        Event::listen(Events::CONNECT, [$this,'connect']);
        Event::listen(Events::SHUTDOWN, [$this,'shutdown']);
    }

    public function connect()
    {
        $client = $this->client;
        $port = (int) env('MQTT_PORT');
        $timeout = (int) env('MQTT_TIMEOUT');

        if($client->isConnected()){
            return;
        }

        $client->connect(
            env('MQTT_HOST'),
            $port,
            $this->connection,
            $timeout
        );
    }

    public function shutdown()
    {
        $client = $this->client;

        if($client->isConnected()){
            Log::info('MQTT Client Disconnected');
            $this->shutdown = true;
            $client->disconnect();
        }
        Log::info('MQTT Daemon Shutdown');
    }

    public function onReceivedMessages(Message $message)
    {
        try{
            event(Events::MESSAGE_RECEIVED, $message);
        }catch (\Exception $e){
            Log::error('Error while handling topic', [$message->getTopic(), $message->getPayload()]);
            Log::error('Message: ', [$e->getMessage()]);
        }
    }

    private function configureClient()
    {
        $client = $this->client;
        $loop = $this->loop;

        $loop->addPeriodicTimer(10, function(){
            event(Events::PUBLISH_DEVICE_STATE);
        });
        $client->on('message',[$this,'onReceivedMessages']);

        $client->on('connect', function($ob) use ($client){
            Log::info('connected to {0}',[env('MQTT_HOST')]);
            $client->subscribe(new DefaultSubscription('#'));
            event(Events::PUBLISH_DEVICE_STATE);
        });

        $client->on('open', function () use ($client) {
            // Network connection established
            Log::info(sprintf("Open: %s:%d\n", $client->getHost(), $client->getPort()));
        });

        $client->on('error', function (\Exception $e) use ($client, $loop) {
            Log::info(sprintf("Error: %s\n", $e->getMessage()), [$e->getCode()]);
            if(!$client->isConnected() && !$this->shutdown){
                sleep(5);//@todo provide better sleep
                \event(Events::CONNECT);
            }
        });

        $client->on('disconnect', function(Connection $connection) use ($client){
            if($this->shutdown){
                Log::info('Disconnected: '. $connection->getClientID());
                return;
            }
            Log::info('retry to connecting client');
            \event(Events::CONNECT);
        });
    }
}
