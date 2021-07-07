<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationOptionsTable extends Migration
{
	public static $table_name = 'variation_options';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::$table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('variation_id');
			$table->string('name', 127);
			$table->unsignedInteger('position')->default(1);
            $table->string('code', 127)->nullable();
			$table->integer('quantity')->default(0);
			$table->decimal('price', 11, 2)->nullable();

            $table->timestamps();
			$table->enum('status', ['active','inactive','deleted'])->default('active');

			// Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('variation_id')->references('id')->on('product_variations');
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
