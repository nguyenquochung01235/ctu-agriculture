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
        Schema::create('tbl_category_vattu', function (Blueprint $table) {
            $table->id('id_category_vattu');
            $table->bigInteger('id_danhmucquydinh')->unsigned()->index();
            $table->foreign('id_danhmucquydinh')->references('id_danhmucquydinh')->on('tbl_danhmucquydinh')->onDelete('cascade');
            $table->string('name_category_vattu');
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
        Schema::dropIfExists('tbl_category_vattu');
    }
};
