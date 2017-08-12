<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('ID_company', 20);
            $table->text('address', 150);
            $table->String('company_email', 50);
            $table->String('company_phone', 25);
            $table->String('contact', 100);
            $table->String('contact_phone', 25);
            $table->String('contact_email', 50);
            $table->timestamps();
            if(env('DB_CONNECTION') == 'pgsql'){
                $table->text('logo')->nullable();
            }
        });
        
        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement("ALTER TABLE company ADD logo MEDIUMBLOB");
        }    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company');
    }
}
