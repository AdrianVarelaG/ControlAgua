<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company')->insert(array(        
            'name'  	=> 'Aguas de Mexico, C.A.',
        	'ID_company' => 'J-45872659-9',
        	'address' => 'México DF, MEX',
        	'company_phone' => '426-543.99.74',
        	'company_email' => 'aguas.mexico@gmail.com',        
        	'contact' => 'Carlos Rodriguez',
        	'contact_phone' => '0426-543.99.74',
        	'contact_email' => 'carlos.rodriguez@gmail.com',
 		));    
    }
}
