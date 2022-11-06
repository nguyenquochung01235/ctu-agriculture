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
        Schema::create('xavien_rolexavien', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('xavien_id_xavien')->unsigned()->index();
            $table->bigInteger('rolexavien_id_role')->unsigned()->index();
            $table->foreign('xavien_id_xavien')->references('id_xavien')->on('tbl_xavien')->onDelete('cascade');
            $table->foreign('rolexavien_id_role')->references('id_role')->on('tbl_rolexavien')->onDelete('cascade');
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
        Schema::dropIfExists('xavien_rolexavien');
    }
};
