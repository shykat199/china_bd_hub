<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('type', 55)->nullable();
            $table->string('app_key', 155)->nullable();
            $table->string('app_secret', 155)->nullable();
            $table->string('username', 55)->nullable();
            $table->string('password', 55)->nullable();
            $table->string('base_url', 99)->nullable();
            $table->string('success_url', 155)->nullable();
            $table->string('return_url', 155)->nullable();
            $table->string('prefix', 25)->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('payment_gateways');
    }
}
