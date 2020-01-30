<?php


namespace Hestia\MqttGateway\Listeners;


use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use BinSoul\Net\Mqtt\Message;
use Hestia\MqttGateway\Model\IRCommandMap;
use Hestia\MqttGateway\Model\IRDeviceState;
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
            $this->saveState($mapped,$payload);
        }
    }

    private function saveState(IRCommandMap $commandMap, $newPayload)
    {
        $stateTopic = $commandMap->getAttribute('StateTopic');
        $command = $commandMap->getAttribute('Command');
        $state = IRDeviceState::where('StateTopic','=',$stateTopic)->firstOrFail();

        if(!$state instanceof IRDeviceState){
            return;
        }

        $payload = $state->getAttribute('Payload');
        $payload[$command] = strtolower($newPayload);

        $state->Payload = $payload;
        $state->save();
        Log::info('Saved new state: ',[$stateTopic, $payload]);
    }

    private function sendIRCode(IRCommandMap $commandMap)
    {
        $client = $this->mqttClient;
        $message = new DefaultMessage(
            $commandMap->getAttribute('SendTopic'),
            $commandMap->getAttribute('Payload')
        );
        $client->publish($message);
        Log::info('Published: ', [$commandMap->getAttribute('SubscribedTopic')]);
    }
}
