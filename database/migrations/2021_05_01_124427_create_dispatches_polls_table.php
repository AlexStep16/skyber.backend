<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchesPollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatches_polls', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('poll_id');
          $table->string('email')->nullable();
          $table->string('fingerprint');
          $table->timestamps();
          $table->foreign('poll_id')->references('id')->on('polls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatches_polls');
    }
}
