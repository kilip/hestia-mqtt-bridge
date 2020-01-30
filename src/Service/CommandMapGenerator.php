<?php


namespace Hestia\MqttGateway\Service;


use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCommandMap;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;

class CommandMapGenerator
{
    public function generate(IRDevice $device)
    {
        $prefix = $device->CommandTopicPrefix;
        $gateway = $device->gateway()->firstOrFail();
        $sendTopic = $gateway->SendCodeTopic;
        $codes = $device->codes();

        /* @var IRCode $code */
        foreach($codes->cursor() as $code){
            $topic = explode('/',$prefix);
            $topic[] = $code->Command;
            $payload = $code->getPayload();
            $payload = json_encode($payload);
            if(!is_null($codePayload = $code->getAttribute('Payload'))){
                $topic[] = $codePayload;
            }
            $attributes = [
                'Device' => $device->getAttribute('GUID'),
                'SubscribedTopic' => implode('/',$topic),
                'SendTopic' => $sendTopic,
                'Payload' => $payload,
            ];
            $ob = new IRCommandMap($attributes);
            $ob->save();
            $ob = null;
        }
    }
}
