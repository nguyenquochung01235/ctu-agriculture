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
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('fullname');
            $table->string('email');
            $table->string('phone_number');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('address');
            $table->string('wallet')->nullable();
            $table->date('dob');
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
        Schema::dropIfExists('tbl_user');
    }
};
