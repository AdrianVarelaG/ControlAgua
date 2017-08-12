<?php

use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('states')->insert(array(
			'name' =>  'Aguascalientes',
			'abbrev' =>  'Ags.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Baja California',
			'abbrev' =>  'BC',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Baja California Sur',
			'abbrev' =>  'BCS',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Campeche',
			'abbrev' =>  'Camp.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Coahuila de Zaragoza',
			'abbrev' =>  'Coah.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Colima',
			'abbrev' =>  'Col.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Chiapas',
			'abbrev' =>  'Chis.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Chihuahua',
			'abbrev' =>  'Chih.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Distrito Federal',
			'abbrev' =>  'DF',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Durango',
			'abbrev' =>  'Dgo.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Guanajuato',
			'abbrev' =>  'Gto.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Guerrero',
			'abbrev' =>  'Gro.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Hidalgo',
			'abbrev' =>  'Hgo.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Jalisco',
			'abbrev' =>  'Jal.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'México',
			'abbrev' =>  'Mex.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Michoacán de Ocampo',
			'abbrev' =>  'Mich.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Morelos',
			'abbrev' =>  'Mor.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Nayarit',
			'abbrev' =>  'Nay.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Nuevo León',
			'abbrev' =>  'NL',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Oaxaca',
			'abbrev' =>  'Oax.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Puebla',
			'abbrev' =>  'Pue.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Querétaro',
			'abbrev' =>  'Qro.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Quintana Roo',
			'abbrev' =>  'Q. Roo',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'San Luis Potosí',
			'abbrev' =>  'SLP',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Sinaloa',
			'abbrev' =>  'Sin.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Sonora',
			'abbrev' =>  'Son.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Tabasco',
			'abbrev' =>  'Tab.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Tamaulipas',
			'abbrev' =>  'Tamps.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Tlaxcala',
			'abbrev' =>  'Tlax.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Veracruz de Ignacio de la Llave',
			'abbrev' =>  'Ver.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Yucatán',
			'abbrev' =>  'Yuc.',
			'status' =>  'A'

		));
		DB::table('states')->insert(array(
			'name' =>  'Zacatecas',
			'abbrev' =>  'Zac.',
			'status' =>  'A'
		));
    }
}
