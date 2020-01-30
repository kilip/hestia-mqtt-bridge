<?php

declare(strict_types=1);

namespace Hestia\MqttGateway\Model;


class IRDevice extends AbstractModel
{
    use UuidTrait;
    const TABLE_NAME = 'mgw_irdevice';

    protected $primaryKey = 'GUID';
    protected $keyType = 'string';

    protected $fillable = [
        'GUID',
        'Name',
        'Gateway',
        'CodeDevice'
    ];

    public function gateway()
    {
        return $this->hasOne(IRGateway::class,'GUID','Gateway');
    }

    public function codes()
    {
        return $this->hasMany(IRCode::class, 'Device', 'CodeDevice');
    }
}
