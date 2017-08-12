<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->char('role',3);
            $table->char('status',1);
            $table->string('created_by',100);            
            $table->timestamps();
            if(env('DB_CONNECTION') == 'pgsql'){
                $table->text('avatar')->nullable();
            }                    
        });

        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement("ALTER TABLE users ADD avatar MEDIUMBLOB");
        }    

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
