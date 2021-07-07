<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    public static $table_name = 'stores';

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
            $table->string('name', 127);
            $table->string('business_name', 127);
            $table->string('slogan', 255)->nullable();
            $table->string('slug', 127);
            $table->string('cnpj', 20);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('domain', 127)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('analytics_id', 127)->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->unsignedBigInteger('cover_id')->nullable();
            $table->unsignedBigInteger('background_id')->nullable();

            $table->timestamps();
            $table->enum('status', ['active','inactive','deleted'])->default('active');

            // Engine & Collation
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('logo_id')->nullable()->references('id')->on('images');
            $table->foreign('cover_id')->nullable()->references('id')->on('images');
            $table->foreign('background_id')->nullable()->references('id')->on('images');
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
