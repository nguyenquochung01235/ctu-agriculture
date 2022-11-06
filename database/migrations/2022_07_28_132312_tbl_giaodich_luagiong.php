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
        Schema::create('tbl_giaodich_luagiong', function (Blueprint $table) {
            $table->id('id_giaodich_luagiong');
            $table->bigInteger('id_xavien')->unsigned()->index();
            $table->foreign('id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->bigInteger('id_nhacungcapvattu')->unsigned()->index();
            $table->foreign('id_nhacungcapvattu')->references('id_nhacungcapvattu')->on('tbl_nhacungcapvattu')->onDelete('cascade');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->bigInteger('id_gionglua')->unsigned()->index();
            $table->foreign('id_gionglua')->references('id_gionglua')->on('tbl_gionglua')->onDelete('cascade');
            $table->integer('soluong');
            $table->integer('status');
            $table->integer('hoptacxa_xacnhan');
            $table->integer('nhacungcap_xacnhan');
            $table->integer('xavien_xacnhan');
            $table->text('description_giaodich');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_giaodich_luagiong');
    }
};
