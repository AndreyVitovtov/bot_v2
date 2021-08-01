<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('chat');
            $table->string('username');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date');
            $table->time('time');
            $table->integer('count_ref')->default(0);
            $table->string('country')->nullable();
            $table->bigInteger('messengers_id')->unsigned();
            $table->integer('access')->default(0);
            $table->integer('active')->nullable();
            $table->integer('start')->default(0);
            $table->integer('access_free')->default(0);
            $table->integer('languages_id')->unsigned()->nullable();
            $table->integer('unsubscribed')->default(0);

            $table->index('languages_id');

            $table->foreign('languages_id')
                ->references('id')->on('languages')
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
        Schema::dropIfExists($this->tableName);
    }
}
