<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partner_id')->unsigned();
            $table->string('name', 50);
            $table->string('description', 200);
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->boolean('active');
            $table->string('price', 50);
            $table->bigInteger('discount');
            $table->enum('stock_status', ['0','1'])->default(0);
            $table->string('supplier', 50);
            $table->string('manual_url', 50);
            $table->string('image', 50);
            $table->timestamps();

            $table->foreign('partner_id')
            ->references('id')
            ->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
