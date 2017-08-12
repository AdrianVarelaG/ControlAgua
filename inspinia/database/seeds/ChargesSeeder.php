<?php

use Illuminate\Database\Seeder;

class ChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('charges')->insert(array(
			'movement_type' =>  'CI',
			'type' =>  'P',
			'description' =>  'IVA',
			'amount' =>  0,
			'percent' =>  10,
			'status' =>  'A',
		));
    }
}
