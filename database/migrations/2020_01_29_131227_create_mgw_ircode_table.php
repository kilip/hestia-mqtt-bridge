<?php

use Hestia\MqttGateway\Model\IRCode;
use Hestia\MqttGateway\Model\IRCodeDevice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgwIrcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRCode::TABLE_NAME, function (Blueprint $table) {
            $table->string('GUID', 36)->primary();
            $table->string('Device', 36);
            $table->string('Command');
            $table->string('Payload')->nullable();
            $table->string('Protocol');
            $table->string('Bits');
            $table->string('Data');
            $table->string('DataLSB');
            $table->string('Repeat')->nullable();

            $table->foreign('Device','ix_code_device')
                ->references('GUID')
                ->on(IRCodeDevice::TABLE_NAME)
                ->onDelete('cascade')
            ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(IRCodeDevice::TABLE_NAME);
    }
}
