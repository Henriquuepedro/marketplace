<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('code', 20);
            $table->string('description', 127)->nullable();

            $table->enum('usage_limit', ['one_per_customer','many_times','limited_times'])->default('one_per_customer');
            $table->unsignedSmallInteger('limit')->nullable();

            $table->enum('products_on_sale', ['include','not_include'])->default('not_include');
            $table->enum('discount_type', ['none','products_amount','total_amount','shipping_amount','products_percent','total_percent','shipping_pecent'])->default('none');
            $table->unsignedDecimal('discount_value', 11, 2);

            $table->unsignedDecimal('min_order_value', 11, 2)->default(0);
            $table->enum('include_shipping', ['yes','no'])->default('no');

            $table->timestamps();
            $table->enum('status', ['active','inactive','deleted'])->default('active');

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
