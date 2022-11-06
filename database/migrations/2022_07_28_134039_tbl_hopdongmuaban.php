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
        Schema::create('tbl_hopdongmuaban', function (Blueprint $table) {
            $table->id('id_hopdongmuaban');
            $table->bigInteger('id_thuonglai')->unsigned()->index();
            $table->foreign('id_thuonglai')->references('id_thuonglai')->on('tbl_thuonglai')->onDelete('cascade');
            $table->bigInteger('id_hoptacxa')->unsigned()->index();
            $table->foreign('id_hoptacxa')->references('id_hoptacxa')->on('tbl_hoptacxa')->onDelete('cascade');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->bigInteger('id_danhmucquydinh')->unsigned()->index();
            $table->foreign('id_danhmucquydinh')->references('id_danhmucquydinh')->on('tbl_danhmucquydinh')->onDelete('cascade');
            $table->bigInteger('id_gionglua')->unsigned()->index();
            $table->foreign('id_gionglua')->references('id_gionglua')->on('tbl_gionglua')->onDelete('cascade');
            $table->string('title_hopdongmuaban');
            $table->longText('description_hopdongmuaban');
            $table->integer('hoptacxa_xacnhan');
            $table->integer('thuonglai_xacnhan');
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
        Schema::dropIfExists('tbl_hopdongmuaban');
    }
};
