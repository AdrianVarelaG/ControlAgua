<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert(array(        
            'app_name'  	=> 'Web Agua',
        	'coin' => 'MXN',
        	'money_format' => 'PC2',
        	'create_notification' => true,
        	'update_notification' => true,        
        	'delete_notification' => true,
        	'created_at' => '2017-01-01',
        	'updated_at' => '2017-01-01'
 		));    
    }
}
