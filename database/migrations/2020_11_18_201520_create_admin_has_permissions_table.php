<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminHasPermissionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'admin_has_permissions';

    /**
     * Run the migrations.
     * @table admin_has_permissions
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('admin_id')->unsigned();
            $table->integer('permissions_id')->unsigned();

            $table->index(['admin_id', 'permissions_id']);

            $table->foreign('admin_id')
                ->references('id')->on('admin')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('permissions_id')
                ->references('id')->on('permissions')
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
