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
        Schema::create('tbl_danhmucquydinh', function (Blueprint $table) {
            $table->id('id_danhmucquydinh');
            $table->bigInteger('id_thuonglai')->unsigned()->index();
            $table->foreign('id_thuonglai')->references('id_thuonglai')->on('tbl_thuonglai')->onDelete('cascade');
            $table->string('name_danhmucquydinh');
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
        Schema::dropIfExists('tbl_danhmucquydinh');
    }
};
