<?php


namespace Test\Hestia\MqttGateway\Model;

use Hestia\MqttGateway\Model\IRCodeDevice;
use Hestia\MqttGateway\Model\IRGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Test\Hestia\MqttGateway\TestCase;


class IRGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $ob = factory(IRGateway::class)->make();
        $ob->save();
        $this->assertNotNull($ob->GUID);
        $this->assertDatabaseHas(IRGateway::TABLE_NAME,[
            'Name' => 'Some Gateway Device',
            'PayloadAvailable' => 'online',
            'PayloadUnavailable' => 'offline',
            'SendCodeTopic' => 'test/send/code',
        ]);
    }

    public function testImportFromJSON()
    {
        $json = file_get_contents(__DIR__.'/../../database/seeds/data/gateway/remote1.json');
        $json = json_decode($json,true);
        $ob = new IRCodeDevice($json);
        $ob->save();

        $this->assertDatabaseHas(IRCodeDevice::TABLE_NAME,[
            'GUID' => $json['GUID']
        ]);
    }
}
