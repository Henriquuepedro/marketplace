<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public static $table_name = 'orders';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::$table_name, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id', 127)->nullable();
            $table->string('payment_method', 127);
            $table->unsignedDecimal('amount', 11, 2);
            $table->unsignedDecimal('shipping', 11, 2);
            $table->unsignedDecimal('authorized_amount', 11, 2)->nullable();
            $table->unsignedDecimal('paid_amount', 11, 2)->nullable();
            $table->unsignedDecimal('refunded_amount', 11, 2)->nullable();
            $table->unsignedTinyInteger('installments')->default(1);

            $table->string('boleto_url', 255)->nullable();
            $table->string('boleto_barcode', 255)->nullable();
            $table->string('boleto_expiration_date', 127)->nullable();

            //$table->string('acquirer_name', 127)->nullable();
            //$table->string('acquirer_id', 127)->nullable();
            //$table->string('acquirer_response_code', 127)->nullable();
            $table->string('authorization_code', 127)->nullable();
            $table->string('tid', 127)->nullable();
            $table->string('nsu', 127)->nullable();

            $table->longText('request_data');
            $table->longText('response_data')->nullable();

            $table->string('tracking_code', 127)->nullable();
            $table->datetime('shipping_date')->nullable();
            $table->datetime('delivery_date')->nullable();

            $table->timestamps();
            $table->enum('status', ['processing', 'authorized', 'paid', 'refunded', 'waiting_payment', 'pending_refund', 'refused', 'in_transit', 'returned', 'delivered'])->default('waiting_payment');
            $table->enum('refuse_reason', ['acquirer', 'antifraud', 'internal_error', 'no_acquirer', 'acquirer_timeout'])->nullable();
            $table->enum('status_reason', ['acquirer', 'antifraud', 'internal_error', 'no_acquirer', 'acquirer_timeout'])->nullable();

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
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
