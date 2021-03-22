<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partner_id')->unsigned();
            // $table->bigInteger('package_id')->unsigned();
            $table->string('name', 50);
            $table->string('description', 200)->nullable();
            $table->enum('status', ['0','1'])->default(1);
            $table->timestamps();

            $table->foreign('partner_id')
            ->references('id')
            ->on('partners');

            /*$table->foreign('package_id')
            ->references('id')
            ->on('packages');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
