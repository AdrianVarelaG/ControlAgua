<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            'name'  => 'Ricardo Machado',
            'password'  => Hash::make('secret'),
            'email'  	=> 'ing.ricardo.machado@gmail.com',
            'created_at' => '2017-01-01 00:00:00',
            'updated_at' => '2017-01-01 00:00:00',
        ));
            
    }
}
