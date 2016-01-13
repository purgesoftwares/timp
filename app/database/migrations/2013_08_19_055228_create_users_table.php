<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	public function up()
	{
	    Schema::create('fbusers', function($table)
	    {
	        $table->increments('id');
	        $table->string('email');
	        $table->string('photo');
	        $table->string('first_name');
	        $table->string('last_name');
	        $table->string('fb_id');
	        //$table->string('password');
	        $table->timestamps();
	    });
	}

	public function down()
	{
	    Schema::drop('fbusers');
	}

}