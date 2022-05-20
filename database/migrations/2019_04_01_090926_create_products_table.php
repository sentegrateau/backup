<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('tax_id')->nullable();
            $table->string('name');
            $table->string('slug',500);
            $table->longText('description');
            $table->string('short_description',1000);
            $table->double('price');
            $table->double('special_price');
            $table->double('tax_rate')->nullable();
            $table->string('size');
            $table->integer('quantity');
            $table->string('product_code');
            $table->string('weight');
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('defence')->default(0);
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
        Schema::dropIfExists('products');
    }
}
