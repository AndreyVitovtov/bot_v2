<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info', function (Blueprint $table) {
            $table->id();
            $table->string('db_address')->nullable();
            $table->string('db_login')->nullable();
            $table->string('db_password')->nullable();
            $table->string('db_name')->nullable();
            $table->string('ftp_type')->nullable();
            $table->string('ftp_address')->nullable();
            $table->string('ftp_login')->nullable();
            $table->string('ftp_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info');
    }
}
