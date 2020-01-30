<?php

use Hestia\MqttGateway\Model\IRCommandMap;
use Hestia\MqttGateway\Model\IRDevice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRCommandMap::TABLE_NAME, function (Blueprint $table) {
            $table->string('GUID', 36)->primary();
            $table->string('Device', 36)->index('ix_command_device');
            $table->string('Command');
            $table->string('SubscribedTopic');
            $table->string('StateTopic');
            $table->string('SendTopic');
            $table->string('Payload');
            $table->timestamps();

            /*$table->foreign('Device')
                ->references('GUID')
                ->on(IRDevice::TABLE_NAME)
                ->onDelete('cascade')
            ;*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('command_map');
    }
}
