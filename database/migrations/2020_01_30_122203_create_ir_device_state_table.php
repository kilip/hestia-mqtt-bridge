<?php

use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRDeviceState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIRDeviceStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRDeviceState::TABLE_NAME, function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('Device',36)->index('ix_device');
            $table->string('StateTopic')->index('ix_state_topic');
            $table->json('Payload');
            $table->timestamps();

            /*
             * @TODO fix foreign definition
            $table->foreign('Device','Device')
                ->on(IRDevice::TABLE_NAME)
                ->references("GUID")
            ;
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(IRDeviceState::TABLE_NAME);
    }
}
