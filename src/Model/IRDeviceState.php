<?php


namespace Hestia\MqttGateway\Model;


class IRDeviceState extends AbstractModel
{
    const TABLE_NAME = 'mgw_irdevice_state';

    protected $primaryKey = 'ID';

    protected $fillable = [
        'Device',
        'StateTopic',
        'Payload'
    ];

    protected $casts = [
        'Payload' => 'array'
    ];
}
