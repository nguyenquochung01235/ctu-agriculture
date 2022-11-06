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
        Schema::create('user_account', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('user_id_user')->unsigned()->index();
            $table->bigInteger('account_id_account')->unsigned()->index();
            $table->foreign('user_id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
            $table->foreign('account_id_account')->references('id_account')->on('tbl_account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_account');
    }
};
