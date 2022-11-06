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
        Schema::create('tbl_lichmuavu', function (Blueprint $table) {
            $table->id('id_lichmuavu');
            $table->bigInteger('id_hoptacxa')->unsigned()->index();
            $table->foreign('id_hoptacxa')->references('id_hoptacxa')->on('tbl_hoptacxa')->onDelete('cascade');
            $table->bigInteger('id_gionglua')->unsigned()->index();
            $table->foreign('id_gionglua')->references('id_gionglua')->on('tbl_gionglua')->onDelete('cascade');
            $table->string('name_lichmuavu');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('status');
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
        Schema::dropIfExists('tbl_lichmuavu');
    }
};
