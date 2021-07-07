<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public static $table_name = 'products';

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
            $table->string('name', 127);
            $table->string('code', 127)->nullable();
            $table->mediumText('description');
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('slug', 127);

            $table->enum('featured', ['yes', 'no'])->default('no');
            $table->enum('new', ['yes', 'no'])->default('yes');
            $table->date('new_until')->nullable();
            $table->enum('eco_friendly', ['yes', 'no'])->default('no');
            $table->enum('free_shipping', ['yes', 'no'])->default('no');

            $table->integer('quantity')->default(0);
            $table->decimal('width', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('length', 5, 2)->nullable();
            $table->decimal('weight', 7, 2)->nullable();

            $table->decimal('price', 11, 2)->nullable();
            $table->decimal('old_price', 11, 2)->nullable();
            $table->decimal('tax', 5, 2)->nullable();

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(self::$table_name);
        Schema::enableForeignKeyConstraints();
    }
}
