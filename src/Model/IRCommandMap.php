<?php


namespace Hestia\MqttGateway\Model;


class IRCommandMap extends AbstractModel
{
    use UuidTrait;

    const TABLE_NAME = 'mgw_command_map';

    protected $fillable = [
        'GUID',
        'Device',
        'Command',
        'StateTopic',
        'SubscribedTopic',
        'SendTopic',
        'Payload'
    ];
}
