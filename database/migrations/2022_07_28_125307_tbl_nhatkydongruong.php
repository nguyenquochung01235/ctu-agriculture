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
        Schema::create('tbl_nhatkydongruong', function (Blueprint $table) {
            $table->id('id_nhatkydongruong');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->bigInteger('id_thuadat')->unsigned()->index();
            $table->foreign('id_thuadat')->references('id_thuadat')->on('tbl_thuadat')->onDelete('cascade');
            $table->bigInteger('id_xavien')->unsigned()->index();
            $table->foreign('id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->bigInteger('id_hoatdongmuavu')->unsigned()->index();
            $table->foreign('id_hoatdongmuavu')->references('id_hoatdongmuavu')->on('tbl_hoatdongmuavu')->onDelete('cascade');
            $table->text('description');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('type');
            $table->integer('status');
            $table->integer('hoptacxa_xacnhan');
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
        Schema::dropIfExists('tbl_nhatkydongruong');
    }
};
