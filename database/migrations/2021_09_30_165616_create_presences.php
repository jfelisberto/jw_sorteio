<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('play_id')->unsigned();
            $table->foreign('play_id')->references('id')->on('players');
            $table->bigInteger('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('matches');
            $table->bigInteger('team_id')->unsigned()->nullable()->default(NULL);
            $table->dateTime('confirmed_at')->nullable()->default(NULL);
            $table->bigInteger('created_by')->unsigned()->nullable()->default(NULL);
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->nullable()->default(NULL);
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->bigInteger('deleted_by')->unsigned()->nullable()->default(NULL);
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->bigInteger('restored_by')->unsigned()->nullable()->default(NULL);
            $table->foreign('restored_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamp('restored_at')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presences');
    }
}
