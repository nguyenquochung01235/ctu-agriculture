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
        Schema::create('tbl_giaodichmuaban_vattu', function (Blueprint $table) {
            $table->id('id_giaodichmuaban_vattu');
            $table->bigInteger('id_xavien')->unsigned()->index();
            $table->foreign('id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->bigInteger('id_nhacungcapvattu')->unsigned()->index();
            $table->foreign('id_nhacungcapvattu')->references('id_nhacungcapvattu')->on('tbl_nhacungcapvattu')->onDelete('cascade');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->bigInteger('id_category_vattu')->unsigned()->index();
            $table->foreign('id_category_vattu')->references('id_category_vattu')->on('tbl_category_vattu')->onDelete('cascade');
            $table->integer('soluong');
            $table->bigInteger('price');
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
        Schema::dropIfExists('tbl_giaodichmuaban_vattu');
    }
};
