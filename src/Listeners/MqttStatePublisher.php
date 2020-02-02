<?php


namespace Hestia\MqttGateway\Listeners;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use Hestia\MqttGateway\Events;
use Hestia\MqttGateway\Model\IRDeviceState;
use Illuminate\Support\Facades\Log;

class MqttStatePublisher
{
    public function handle()
    {
        try{
            $states = IRDeviceState::all();
            foreach($states as $data){
                $this->publish($data);
            }
        }catch (\Exception $e){
            Log::error('Error while publishing states');
            Log::error('Message: '.$e->getMessage());
        }

    }

    private function publish(IRDeviceState $state)
    {
        $topic = $state->getAttribute('StateTopic').'/tele';
        $payload = $state->getAttribute('Payload');
        event(Events::PUBLISH_MESSAGE,[$topic, $payload]);
    }
}
