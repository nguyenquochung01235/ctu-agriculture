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
        Schema::create('tbl_giaodichmuaban_lua', function (Blueprint $table) {
            $table->id('id_giaodichmuaban_lua');
            $table->bigInteger('id_xavien')->unsigned()->index();
            $table->foreign('id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->bigInteger('id_thuonglai')->unsigned()->index();
            $table->foreign('id_thuonglai')->references('id_thuonglai')->on('tbl_thuonglai')->onDelete('cascade');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->string('name_lohang');
            $table->bigInteger('soluong');
            $table->bigInteger('price_lohang');
            $table->string('img_lohang');
            $table->text('description_giaodich')->nullable();
            $table->integer('status');
            $table->integer('hoptacxa_xacnhan');
            $table->integer('nhacungcap_xacnhan');
            $table->integer('xavien_xacnhan');
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
        Schema::dropIfExists('tbl_giaodichmuaban_lua');
    }
};
