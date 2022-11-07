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
        Schema::create('tbl_comment', function (Blueprint $table) {
            $table->id('id_comment');
            $table->bigInteger('id_post')->unsigned()->index();
            $table->foreign('id_post')->references('id_post')->on('tbl_post')->onDelete('cascade');
            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
            $table->bigInteger('parent_id');
            $table->text('content');
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
        Schema::dropIfExists('tbl_comment');
    }
};
