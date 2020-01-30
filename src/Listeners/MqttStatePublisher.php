<?php


namespace Hestia\MqttGateway\Listeners;


use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use Hestia\MqttGateway\Model\IRDeviceState;
use Illuminate\Support\Facades\Log;

class MqttStatePublisher
{
    /**
     * @var ReactMqttClient
     */
    private $mqttClient;

    public function __construct(ReactMqttClient $mqttClient)
    {
        $this->mqttClient = $mqttClient;
    }

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
        $client = $this->mqttClient;

        $message = new DefaultMessage(
            $state->getAttribute('StateTopic'),
            json_encode($state->getAttribute('Payload'))
        );
        $client->publish($message);
        Log::info('Published: '.$state->getAttribute('StateTopic'));
    }
}
