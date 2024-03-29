<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_options', function (Blueprint $table) {
            $table->id();
            $table->string('alignment')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->unsignedBigInteger('media_id');
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
        Schema::dropIfExists('image_options');
    }
}
