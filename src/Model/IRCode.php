<?php


namespace Hestia\MqttGateway\Model;


class IRCode extends AbstractModel
{
    use UuidTrait;

    const TABLE_NAME = 'mgw_ircode';

    protected $primaryKey = "GUID";
    protected $keyType = "string";
    protected $fillable = [
        'GUID',
        'Device',
        'Command',
        'Payload',
        'Protocol',
        'Bits',
        'Data',
        'DataLSB',
        'Repeat',
    ];

    /**
     * @return array
     */
    public function getPayload()
    {
        $valid = ['Protocol', 'Bits','Data','DataLSB','Repeat'];
        $data = [];
        foreach($valid as $name){
            $data[$name] = $this->getAttribute($name);
        }

        return $data;
    }
}
