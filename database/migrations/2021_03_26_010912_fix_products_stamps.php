<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixProductsStamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->enum('eco_friendly_accepted', ['yes','no'])->default('no')->after('eco_friendly');
            $table->enum('cruelty_free', ['yes','no'])->default('no')->after('eco_friendly_accepted');
            $table->enum('cruelty_free_accepted', ['yes','no'])->default('no')->after('cruelty_free');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('eco_friendly_accepted');
            $table->dropColumn('cruelty_free');
            $table->dropColumn('cruelty_free_accepted');
        });
    }
}
