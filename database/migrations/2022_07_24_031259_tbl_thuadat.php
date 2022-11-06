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
        Schema::create('tbl_thuadat', function (Blueprint $table) {
            $table->id('id_thuadat');
            $table->bigInteger('id_xavien')->unsigned()->index();
            $table->foreign('id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->string('address');
            $table->string('location')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('description')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('tbl_thuadat');
    }
};
