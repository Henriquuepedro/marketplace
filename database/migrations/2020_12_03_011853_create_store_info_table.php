<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreInfoTable extends Migration
{
    public static $table_name = 'store_info';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::$table_name, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('bank_branch', 7);
            $table->string('bank_branch_dv', 3)->nullable();
            $table->string('bank_account', 11);
            $table->string('bank_account_dv', 3);
            $table->enum('bank_account_type', ['conta_corrente', 'conta_poupanca', 'conta_corrente_conjunta', 'conta_poupanca_conjunta'])->default('conta_corrente');
            $table->string('account_holder_name', 127);
            $table->string('account_holder_doc', 20);

            $table->string('bank_account_id', 127)->nullable();
            $table->string('recipient_id', 127)->nullable();
            $table->string('recipient_status', 63)->nullable();
            $table->string('status_reason', 255)->nullable();

            $table->timestamps();
            $table->enum('status', ['active','inactive','deleted'])->default('active');

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(self::$table_name);
        Schema::enableForeignKeyConstraints();
    }
}
