<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public static $table_name = 'addresses';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::$table_name, function (Blueprint $table) {
            $table->id();
            $table->string('alias', 127)->nullable();
            $table->enum('type', ['billing', 'shipping'])->default('shipping');
            $table->string('address', 255);
            $table->string('number', 47);
            $table->string('complement', 127)->nullable();
            $table->string('neighborhood', 127);
            $table->string('city', 127);
            $table->string('zipcode', 9);
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('country_id');
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('reference')->nullable();

            $table->timestamps();
            $table->enum('status', ['active','inactive','deleted'])->default('active');

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('country_id')->references('id')->on('countries');
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
