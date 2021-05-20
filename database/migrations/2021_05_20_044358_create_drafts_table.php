<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->on('users')->references('id');
            $table->string('type');
            $table->string('title');
            $table->enum('category', ['draft', 'quotation'])->default('draft');
            $table->string('quotation_no')->nullable();
            $table->integer('total_amount')->nullable();
            $table->date('validity')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('drafts');
    }
}
