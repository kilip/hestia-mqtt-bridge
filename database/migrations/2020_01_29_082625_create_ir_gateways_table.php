<?php

use Hestia\MqttGateway\Model\IRGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIRGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(IRGateway::TABLE_NAME, function(Blueprint $table){
            $table->string('GUID', 36)->primary();
            $table->string('Name');
            $table->string('AvailableTopic')->nullable(true);
            $table->string('PayloadAvailable')->default('online');
            $table->string('PayloadUnavailable')->default('offline');
            $table->string('SendCodeTopic');
            $table->string('ReceiveCodeTopic');

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
        Schema::dropIfExists(IRGateway::TABLE_NAME);
    }
}
