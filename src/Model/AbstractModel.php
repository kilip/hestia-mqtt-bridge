<?php


namespace Hestia\MqttGateway\Model;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    const TABLE_NAME = "";

    protected $primaryKey = 'GUID';

    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        $this->table = static::TABLE_NAME;
        parent::__construct($attributes);
    }
}
