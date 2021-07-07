<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // First, add the Master User
        $this->call( MasterUserSeeder::class );

        // Second, load Countries and States
        $this->call( CountriesSeeder::class );
        $this->call( StatesSeeder::class );

        // Now, write the Abilities for Customers and Sellers
        $this->call( AbilitiesSeeder::class );

        // Default categories
        $this->call( CategoriesSeeder::class );

        // Images & Backgrounds
        $this->call( ImagesSeeder::class );
        $this->call( StoreBackgroundsSeeder::class );

        // Banks
        $this->call( BanksSeeder::class );
    }
}
