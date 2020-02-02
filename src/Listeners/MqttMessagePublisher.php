<?php

declare(strict_types=1);

namespace Hestia\MqttGateway\Listeners;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use Illuminate\Support\Facades\Log;

class MqttMessagePublisher
{
    /**
     * 
     */
    private $mqttClient;

    public function __construct(ReactMqttClient $mqttClient)
    {
        $this->mqttClient = $mqttClient;
    }

    public function handle($topic, $payload)
    {
        if(is_array($payload)){
            $payload = json_encode($payload);
        }

        $client = $this->mqttClient;
        $message = new DefaultMessage(
            $topic,
            $payload
        );
        $client->publish($message);
        //Log::info('Published: ',[$topic, $payload]);
    }
}