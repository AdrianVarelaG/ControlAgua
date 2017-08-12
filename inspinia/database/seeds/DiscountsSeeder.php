<?php

use Illuminate\Database\Seeder;

class DiscountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('discounts')->insert(array(
			'movement_type' =>  'DE',
			'type' =>  'P',
			'description' =>  'Descuento por 3ra Edad',
			'amount' =>  0,
			'percent' =>  8,
			'status' =>  'A',
		));
    }
}
