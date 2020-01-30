<?php

use Hestia\MqttGateway\Model\IRCodeDevice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgwIrcodeDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRCodeDevice::TABLE_NAME, function (Blueprint $table) {
            $table->string('GUID', 36)->primary();

            $table->string('Name');
            $table->string('Description')->nullable();
            $table->string('Brand')->nullable();
            $table->string('Type')->nullable();
            $table->string('ModelID')->nullable();

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
