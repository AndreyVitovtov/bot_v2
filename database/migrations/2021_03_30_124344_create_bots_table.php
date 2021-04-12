<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token');
            $table->bigInteger('messengers_id')->unsigned();
            $table->integer('languages_id')->unsigned();

            $table->index('messengers_id');
            $table->index('languages_id');

            $table->foreign('messengers_id')
                ->references('id')->on('messengers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('languages_id')
                ->references('id')->on('languages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('bots_id');
            $table->foreign('bots_id')
                ->references('id')->on('bots')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('users', function (Blueprint $table) {
//            $table->dropForeign('users_bots_id_foreign');
//        });
        Schema::dropIfExists('bots');
    }
}
