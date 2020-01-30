<?php


namespace Hestia\MqttGateway\Listeners;


use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use BinSoul\Net\Mqtt\Message;
use Hestia\MqttGateway\Model\IRCommandMap;
use Illuminate\Support\Facades\Log;

class MqttTopicListener
{
    /**
     * @var ReactMqttClient
     */
    private $mqttClient;

    public function __construct(ReactMqttClient $mqttClient)
    {
        $this->mqttClient = $mqttClient;
    }

    public function handle(Message $message)
    {
        $topic = $message->getTopic();
        $payload = trim($message->getPayload());

        if(is_numeric($payload)){
            $payload = (int) $payload;
        }

        $key = $topic.'/'.$payload;
        $key = strtolower($key);
        $mapped = IRCommandMap::where('SubscribedTopic','=', $key)->first();
        if($mapped instanceof IRCommandMap){
            $this->sendIRCode($mapped);
            Log::info('Published: ', [$mapped->getAttribute('SubscribedTopic')]);
        }
        return;
    }

    private function sendIRCode(IRCommandMap $commandMap)
    {
        $client = $this->mqttClient;
        $message = new DefaultMessage(
            $commandMap->getAttribute('SendTopic'),
            $commandMap->getAttribute('Payload')
        );
        $client->publish($message);
    }
}
