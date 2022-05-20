<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('coupon_code');
            $table->double('min_order');
            $table->double('discount_amount');
            $table->tinyInteger('discount_type')->default(0); //if 0 then not percentage
            $table->tinyInteger('redeem_once')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->tinyInteger('never_expire')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
