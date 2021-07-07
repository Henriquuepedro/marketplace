<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public static $table_name = 'categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::$table_name, function (Blueprint $table) {
            $table->id();
            $table->string('name', 127);
            $table->string('slug', 255);
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('cover_id')->nullable();

            // Nested Set columns
            $table->nestedSet();

            $table->timestamps();
            $table->enum('status', ['active','inactive','deleted'])->default('active');

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('cover_id')->nullable()->references('id')->on('images');
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
