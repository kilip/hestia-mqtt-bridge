<?php


namespace Hestia\MqttGateway\Service;


use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCommandMap;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRDeviceState;

class CommandMapGenerator
{
    public function generate(IRDevice $device)
    {
        $prefix = $device->CommandTopicPrefix;
        $stateTopic = $prefix.'/state';
        $gateway = $device->gateway()->firstOrFail();
        $sendTopic = $gateway->SendCodeTopic;
        $codes = $device->codes();

        $initialPayload = [];
        /* @var IRCode $code */
        foreach($codes->cursor() as $code){
            $topic = explode('/',$prefix);
            $command = $code->Command;
            $topic[] = $command;
            $payload = $code->getPayload();
            $payload = json_encode($payload);
            if(!is_null($codePayload = $code->getAttribute('Payload'))){
                $topic[] = $codePayload;
            }
            $attributes = [
                'Device' => $device->getAttribute('GUID'),
                'Command' => $command,
                'SubscribedTopic' => implode('/',$topic),
                'StateTopic' => $stateTopic,
                'SendTopic' => $sendTopic,
                'Payload' => $payload
            ];
            $ob = new IRCommandMap($attributes);
            $ob->save();
            $ob = null;
            $initialPayload[$code->Command] = null;
        }

        $state = new IRDeviceState([
            'Device' => $device->getAttribute('GUID'),
            'StateTopic' => $stateTopic,
            'Payload' => $initialPayload
        ]);
        $state->save();
    }
}
