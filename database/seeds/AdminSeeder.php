<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            'username' => 'Master Admin',
            'email' => 'tucana4tech@gmail.com',
            'password' => bcrypt('tucana4tech@gmail')
        ]);
    }
}
