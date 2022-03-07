<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([

            'username' => 'umberto', 
            'password' => bcrypt('okok'),
            'image_id' => null,
            'email' => 'umberto.labarbera@student.univaq.it',
            'role_id' => 1
            ]
        );

    }
}
