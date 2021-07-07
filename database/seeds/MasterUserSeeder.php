<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\Bouncer;
use App\Models\Auth\User;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Master user data
        $master_name = 'Master User';
        $master_mail = 'master@keewe.com.br';
        $master_pass = 'q1w2e3r4';

        // Gets the Bouncer instance
        $bouncer  = Bouncer::create();

        // Creates the Master Role
        $master_role = $bouncer->role()->firstOrCreate([
            'name' => 'master',
            'title' => 'Master User'
        ]);
        $bouncer->allow( $master_role )->everything();


        // Check if Master exists
        $master = User::where('username', '=', $master_mail)->first();

        if( $master )
        {
            // Updates Master data
            $master->fullname = $master_name;
            $master->password = \bcrypt($master_pass);
            $master->save();

            // Updates the Master Role
            $bouncer->assign( $master_role )->to( $master );

            echo 'The Master User already exists and was updated.', PHP_EOL;
            return;
        }

        // Creates the new Master User
        $master = new User([
            'fullname' => $master_name,
            'username' => $master_mail,
            'password' => \bcrypt($master_pass)
        ]);
        $master->save();

        // Assign Master User to Master Role
        $bouncer->assign( $master_role )->to( $master );
    }
}
