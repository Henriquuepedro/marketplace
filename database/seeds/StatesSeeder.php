<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to SQL file
        $sql_path = \base_path() . '/database/sql/states.sql';
        $sql_data = \file_get_contents( $sql_path );

        DB::unprepared( $sql_data );
    }
}
