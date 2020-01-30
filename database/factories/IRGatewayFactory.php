<?php

use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCodeDevice;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;

/* @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(IRGateway::class, function () {
    return [
        'GUID' => 'd1adb164-a342-45b3-bd2c-ffa1926b724a',
        'Name' => 'Some Gateway Device',
        'SendCodeTopic' => 'test/send/code',
        'ReceiveCodeTopic' => 'receive/code/topic'
    ];
});

$factory->define(IRCodeDevice::class, function(){
    return [
        'GUID' => 'd440468d-4acd-4a42-b0fa-d93c1c9af985',
        'Name' => 'Some IR Code Device',
    ];
});

$factory->define(IRCode::class, function(){
   return [
       'Device' => 'd440468d-4acd-4a42-b0fa-d93c1c9af985',
       'Command' => 'power',
       'Payload' => 'on',
       'Protocol' => 'LG',
       'Bits' => 24,
       'Data' => 'data',
       'DataLSB' => 'data_lsb',
       'Repeat' => 0
   ];
},'dev1');

$factory->define(IRCode::class, function(){
    return [
        'Device' => 'd440468d-4acd-4a42-b0fa-d93c1c9af985',
        'Command' => 'power',
        'Payload' => 'off',
        'Protocol' => 'LG',
        'Bits' => 24,
        'Data' => 'data',
        'DataLSB' => 'data_lsb',
        'Repeat' => 0
    ];
},'dev2');


$factory->define(IRDevice::class, function(){
    return [
        'GUID' => '677be930-3ee2-4a5d-89a4-78491bf9005f',
        'Name' => 'Some Device To Remote',
        'CommandTopicPrefix' => 'mbr/ac',
        'Gateway' => 'd1adb164-a342-45b3-bd2c-ffa1926b724a',
        'CodeDevice' => 'd440468d-4acd-4a42-b0fa-d93c1c9af985'
    ];
});


