<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'contacts';

    /**
     * Run the migrations.
     * @table contacts
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('contacts_type_id')->unsigned();
            $table->integer('users_id')->unsigned();
            $table->text('text');
            $table->date('date');
            $table->time('time');

            $table->index(['contacts_type_id', 'users_id']);

            $table->foreign('contacts_type_id')
                ->references('id')->on('contacts_type')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('users_id')
                ->references('id')->on('users')
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
