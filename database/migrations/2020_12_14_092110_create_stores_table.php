<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');			
            $table->string('name');
            $table->string('pharmacy_license',500);
            $table->string('gst_no',500);
            $table->string('company_reg_no',500);        
            $table->string('address');           
            $table->string('license_image');    
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

	//php artisan make:migration add_paid_to_users_table --table=users
	
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
