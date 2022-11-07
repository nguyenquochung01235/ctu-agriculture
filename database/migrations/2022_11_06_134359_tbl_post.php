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
        Schema::create('tbl_post', function (Blueprint $table) {
            $table->id('id_post');
            $table->string('title_post');
            $table->string('short_description');
            $table->string('description');
            $table->longText('content');
            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
            $table->integer('view');
            $table->integer('status');
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
        Schema::dropIfExists('tbl_post');
    }
};
