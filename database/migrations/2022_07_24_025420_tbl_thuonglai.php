<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_thuonglai', function (Blueprint $table) {
            $table->id('id_thuonglai');
            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
            $table->string('name_thuonglai');
            $table->string('thumbnail')->nullable();
            $table->string('img_background')->nullable();
            $table->string('description')->nullable();
            $table->integer('active');
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
        Schema::dropIfExists('tbl_thuonglai');
    }
};
