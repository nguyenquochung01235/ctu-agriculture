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
        Schema::create('tbl_hoptacxa', function (Blueprint $table) {
            $table->id('id_hoptacxa');
            $table->string('name_hoptacxa');
            $table->string('phone_number');
            $table->string('email');
            $table->string('address');
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
        Schema::dropIfExists('tbl_hoptacxa');
    }
};
