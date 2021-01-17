<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDataTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'payment_data';

    /**
     * Run the migrations.
     * @table payment_data
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('qiwi_token')->nullable();
            $table->string('qiwi_webhook_id')->nullable();
            $table->string('qiwi_public_key')->nullable();
            $table->string('yandex_money_secret_key')->nullable();
            $table->string('yandex_money_wallet')->nullable();
            $table->string('webmoney_wallet')->nullable();
            $table->string('paypal_facilitator')->nullable();
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
