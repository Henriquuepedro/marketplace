<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProductsTable extends Migration
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
            $cols = [
                'MODIFY COLUMN width SMALLINT UNSIGNED NULL',
                'MODIFY COLUMN height SMALLINT UNSIGNED NULL',
                'MODIFY COLUMN length SMALLINT UNSIGNED NULL',
                'MODIFY COLUMN weight SMALLINT UNSIGNED NULL',
            ];

            $query = 'ALTER TABLE products ' . implode(', ', $cols);

            DB::unprepared( $query );

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
        });
    }
}
