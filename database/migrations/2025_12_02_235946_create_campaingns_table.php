<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaingnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('banner_title')->nullable();
            $table->text('video')->nullable();
            $table->text('banner')->nullable();
            $table->string('slug', 255);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('review')->nullable();
            $table->integer('product_id')->nullable();
            $table->text('image_one')->nullable();
            $table->text('image_two')->nullable();
            $table->text('image_three')->nullable();
            $table->string('status', 55)->nullable();
            $table->dateTime('deadline')->nullable();
            $table->string('top_title_1', 255)->nullable();
            $table->string('top_title_2', 255)->nullable();
            $table->string('heading_1', 255)->nullable();
            $table->string('feature_1', 255)->nullable();
            $table->string('feature_2', 255)->nullable();
            $table->string('heading_2', 255)->nullable();
            $table->string('heading_3', 255)->nullable();
            $table->string('heading_4', 255)->nullable();
            $table->string('note', 255)->nullable();
            $table->string('billing_details', 255)->nullable();
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
        Schema::dropIfExists('campaingns');
    }
}
