<?php

declare(strict_types=1);

namespace Hestia\MqttGateway\Service;

use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCodeDevice;

class ImportRemoteCode
{
    /**
     * @param string $json Filename or json string
     * @throws \Exception
     */
    public function fromJSON(string $json)
    {
        if(is_file($json)){
            $json = file_get_contents($json);
        }

        $json = json_decode($json, true);

        if(json_last_error()){
            throw new \Exception(json_last_error_msg());
        }

        $codes = [];
        if(isset($json['Codes'])){
            $codes = $json['Codes'];
            unset($json['Codes']);
        }

        $device = new IRCodeDevice($json);
        $device->save();

        foreach($codes as $info){
            $info['Device'] = $json['GUID'];
            $code = new IRCode($info);
            $code->save();
        }
    }
}
