<?php

use Illuminate\Database\Seeder;

class RatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('rates')->insert(array(
			'movement_type' =>  'CT',
			'name' =>  'Monto Fijo',
			'amount' =>  40,
			'description' =>  '',
			'created_by' =>  '',
			'status' =>  'A',
		));
    }
}
