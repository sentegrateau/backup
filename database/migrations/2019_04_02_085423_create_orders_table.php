<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('ship_id');
            $table->string('txn_id')->nullable();
            $table->string('track_num')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->double('order_total');
            $table->integer('quantity');
            $table->string('coupon', 100)->nullable();
            $table->double('coupon_amt')->nullable();
            $table->string('payment_method')->nullable();
            $table->tinyInteger('payment_status')->default(0);
            $table->tinyInteger('status')->default(0)->comment('-1=>Cancelled ,0=>Pending, 1=>Completed ');
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
        Schema::dropIfExists('orders');
    }
}
