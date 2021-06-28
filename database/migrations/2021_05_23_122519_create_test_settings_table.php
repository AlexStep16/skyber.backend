<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->boolean('access_for_all')->default(true);
            $table->boolean('password_access')->default(false);
            $table->boolean('is_list')->default(true);
            $table->boolean('is_right_questions')->default(false);
            $table->boolean('is_resend')->default(false);
            $table->boolean('has_statistic')->default(true);
            $table->boolean('is_reanswer')->default(true);
            $table->string('password')->nullable();
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
        Schema::dropIfExists('test_settings');
    }
}
