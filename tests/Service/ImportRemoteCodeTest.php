<?php

namespace Test\Hestia\MqttGateway\Service;

use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Service\ImportRemoteCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Test\Hestia\MqttGateway\TestCase;

class ImportRemoteCodeTest extends TestCase
{
    use RefreshDatabase;

    public function testFromJSON()
    {
        $ob = new ImportRemoteCode();
        $ob->fromJSON(__DIR__.'/../../database/seeds/data/ir-codes/1001.json');

        $this->assertDatabaseHas(IRCode::TABLE_NAME,[
            'Command' => 'power',
            'Payload' => 'on'
        ]);
        $this->assertDatabaseHas(IRCode::TABLE_NAME,[
            'Command' => 'mode',
            'Payload' => 'heat'
        ]);

    }
}
