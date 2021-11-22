<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsHasLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots_has_languages', function (Blueprint $table) {
            $table->integer('bots_id')->unsigned();
            $table->integer('languages_id')->unsigned();

            $table->index(['bots_id', 'languages_id']);

            $table->foreign('bots_id')
                ->references('id')->on('bots')
                ->onUpdate('cascade');

            $table->foreign('languages_id')
                ->references('id')->on('languages')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots_has_languages');
    }
}
