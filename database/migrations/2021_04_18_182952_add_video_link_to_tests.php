<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoLinkToTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
          $table->string('video_link')->nullable();
        });
        Schema::table('polls', function (Blueprint $table) {
          $table->string('video_link')->nullable();
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
        $table->dropColumn('video_link');
      });
      Schema::table('polls', function (Blueprint $table) {
        $table->dropColumn('video_link');
      });
    }
}
