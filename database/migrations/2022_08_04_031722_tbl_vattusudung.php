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
        Schema::create('tbl_vattusudung', function (Blueprint $table) {
            $table->id('id_vattusudung');
            $table->bigInteger('id_nhatkydongruong')->unsigned()->index();
            $table->foreign('id_nhatkydongruong')->references('id_nhatkydongruong')->on('tbl_nhatkydongruong')->onDelete('cascade');
            $table->bigInteger('id_giaodichmuaban_vattu')->unsigned()->index();
            $table->foreign('id_giaodichmuaban_vattu')->references('id_giaodichmuaban_vattu')->on('tbl_giaodichmuaban_vattu')->onDelete('cascade');
            $table->integer('soluong');
            $table->date('timeuse');
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
        Schema::dropIfExists('tbl_vattusudung');
    }
};
