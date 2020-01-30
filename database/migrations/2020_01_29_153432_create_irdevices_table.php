<?php

use Hestia\MqttGateway\Model\IRCodeDevice;
use Hestia\MqttGateway\Model\IRDevice;
use Hestia\MqttGateway\Model\IRGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIRDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRDevice::TABLE_NAME, function (Blueprint $table) {
            $table->string("GUID");
            $table->string('Name');
            $table->string('CommandTopicPrefix')->nullable();
            $table->string('Gateway',36);
            $table->string('CodeDevice', 36);
            $table->timestamps();

            $table->foreign('Gateway')
                ->on(IRGateway::TABLE_NAME)
                ->references('GUID')
            ;
            $table->foreign('CodeDevice')
                ->on(IRCodeDevice::TABLE_NAME)
                ->references('GUID')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(IRDevice::TABLE_NAME);
    }
}
