<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_apis', function (Blueprint $table) {
            $table->id();
            $table->string('type', 55)->nullable();
            $table->string('api_key', 155)->nullable();
            $table->string('secret_key', 155)->nullable();
            $table->string('url', 99)->nullable();
            $table->text('token')->nullable();
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
        Schema::dropIfExists('courier_apis');
    }
}
