<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('admin')->length(1)->nullable()->default(NULL);
            $table->smallInteger('technician')->length(1)->nullable()->default(NULL);
            $table->bigInteger('play_id')->unsigned()->nullable()->default(NULL);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paly_id');
        });
    }
}
