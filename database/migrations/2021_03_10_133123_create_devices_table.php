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
            $table->unsignedBigInteger('partner_id');
            $table->string('name', 50);
            $table->string('description', 200);
            $table->string('brand', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->boolean('active')->nullable();
            $table->string('price', 50)->default(0);
            $table->bigInteger('discount');
            $table->enum('stock_status', ['0','1'])->default(0);
            $table->string('supplier', 50);
            $table->string('manual_url', 50)->nullable();
            $table->text('image', 50)->nullable();
            $table->enum('status', ['0','1'])->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('partner_id')
            ->references('id')
            ->on('users');
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
