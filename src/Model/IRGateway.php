<?php


namespace Hestia\MqttGateway\Model;


use Illuminate\Database\Eloquent\Model;

class IRGateway extends AbstractModel
{
    use UuidTrait;

    const TABLE_NAME = 'mgw_irgateway';

    protected $fillable = [
        'GUID',
        'Name',
        'AvailableTopic',
        'PayloadAvailable',
        'PayloadUnavailable',
        'SendCodeTopic',
        'ReceiveCodeTopic'
    ];
}
