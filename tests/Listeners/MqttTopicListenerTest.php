<?php

namespace Test\Hestia\MqttGateway\Listeners;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use Hestia\MqttGateway\Listeners\MqttTopicListener;
use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCodeDevice;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;
use Hestia\MqttGateway\Service\CommandMapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Test\Hestia\MqttGateway\TestCase;

class MqttTopicListenerTest extends TestCase
{
    use RefreshDatabase;

    private function buildDatabase()
    {
        factory(IRGateway::class)->make()->save();
        factory(IRCodeDevice::class)->make()->save();
        factory(IRCode::class,'dev1')->make()->save();
        factory(IRCode::class,'dev2')->make()->save();

        $device = factory(IRDevice::class)->make();
        $generator = new CommandMapGenerator();

        $generator->generate($device);
    }

    public function testHandleMessage()
    {
        $this->buildDatabase();
        $client = $this->createMock(ReactMqttClient::class);
        $client->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(DefaultMessage::class))
        ;
        $message1 = new DefaultMessage('mbr/ac/power','on');
        $message2 = new DefaultMessage('mbr/ac/power','foo');
        $ob = new MqttTopicListener($client);

        $ob->handle($message1);
        $ob->handle($message2);
    }
}
