<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('url', 99)->nullable();
            $table->string('api_key', 155)->nullable();
            $table->string('serderid', 155)->nullable();
            $table->string('order', 11)->nullable();
            $table->string('forget_pass', 11)->nullable();
            $table->string('password_g', 11)->nullable();
            $table->string('status', 11)->nullable();
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
        Schema::dropIfExists('sms_gateways');
    }
}
