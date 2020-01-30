<?php


namespace Test\Hestia\MqttGateway\Service;


use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCodeDevice;
use Hestia\MqttGateway\Model\IRCommandMap;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;
use Hestia\MqttGateway\Service\CommandMapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Test\Hestia\MqttGateway\TestCase;

class CommandMapGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function testImport()
    {
        factory(IRGateway::class)->make()->save();
        factory(IRCodeDevice::class)->make()->save();
        factory(IRCode::class,'dev1')->make()->save();
        factory(IRCode::class,'dev2')->make()->save();

        $device = factory(IRDevice::class)->make();
        $generator = new CommandMapGenerator();

        $generator->generate($device);

        $this->assertDatabaseHas(IRCommandMap::TABLE_NAME,[
            'SubscribedTopic' => 'mbr/ac/power/on'
        ]);
        $this->assertDatabaseHas(IRCommandMap::TABLE_NAME,[
            'SubscribedTopic' => 'mbr/ac/power/off'
        ]);
    }
}
