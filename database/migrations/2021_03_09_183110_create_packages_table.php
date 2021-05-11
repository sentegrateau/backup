<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partner_id')->unsigned();
            $table->string('name', 50);
            $table->string('description', 200)->nullable();
            $table->enum('status', ['0','1'])->default(1);
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
