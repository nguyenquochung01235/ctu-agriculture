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
        Schema::create('tbl_hoatdongmuavu', function (Blueprint $table) {
            $table->id('id_hoatdongmuavu');
            $table->bigInteger('id_lichmuavu')->unsigned()->index();
            $table->foreign('id_lichmuavu')->references('id_lichmuavu')->on('tbl_lichmuavu')->onDelete('cascade');
            $table->string('name_hoatdong');
            $table->text('description_hoatdong');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('status');
            $table->string('attach');
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
        Schema::dropIfExists('tbl_hoatdongmuavu');
    }
};
