<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpToTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
          $table->string('ip')->nullable();
        });
        Schema::table('polls', function (Blueprint $table) {
          $table->string('ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tests', function (Blueprint $table) {
          $table->dropColumn('ip');
        });
        Schema::table('polls', function (Blueprint $table) {
          $table->dropColumn('ip');
        });
    }
}
