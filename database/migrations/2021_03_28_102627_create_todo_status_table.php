<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::table('todo', function (Blueprint $table) {
            $table->index('status');
            $table->foreign('status')
                ->references('id')->on('todo_status')
                ->onDelete('cascade')
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
        Schema::dropIfExists('todo');
        Schema::dropIfExists('todo_status');
    }
}
