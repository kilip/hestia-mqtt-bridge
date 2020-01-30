<?php


namespace Hestia\MqttGateway\Model;


class IRCodeDevice extends AbstractModel
{
    const TABLE_NAME = 'mgw_ircode_device';

    protected $primaryKey = 'GUID';

    protected $keyType = 'string';

    protected $fillable = [
        'GUID',
        'Name',
        'Description',
        'Brand',
        'ModelID'
    ];

    protected $casts = [
        'GUID' => 'string'
    ];

    protected function codes()
    {
        return $this->hasMany(IRCode::class, 'Device', 'GUID');
    }
}
