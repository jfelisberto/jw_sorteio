<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 155)->nullable()->default(NULL);
            $table->bigInteger('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('matches');
            $table->bigInteger('attacker')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('midfield_left')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('midfield_right')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('wing_left')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('wing_right')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('defender')->unsigned()->nullable()->default(NULL);
            $table->bigInteger('goalkeeper')->unsigned()->nullable()->default(NULL);
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
        Schema::dropIfExists('teams');
    }
}
