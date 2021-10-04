<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 155)->nullable()->default(NULL);
            $table->dateTime('match_at')->nullable()->default(NULL);
            $table->string('address', 255)->nullable()->default(NULL);
            $table->string('location', 255)->nullable()->default(NULL);
            $table->dateTime('created_team_at')->nullable()->default(NULL);
            $table->dateTime('closed_at')->nullable()->default(NULL);
            $table->bigInteger('user_id')->unsigned()->nullable()->default(NULL);
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('matches');
    }
}
