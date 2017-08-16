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
			'observation' =>  'Monto Fijo',
			'created_by' =>  '',
			'status' =>  'A',
			'created_at' =>  '2017-01-01',
			'updated_at' =>  '2017-01-01',
		));
    }
}
