<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Masterstaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masterstaff', function (Blueprint $table) {
            $table->string('Kode');
            $table->string('StaffName');
            $table->string('Password');
            $table->string('Phone')->nullable();
            $table->string('Email')->nullable();
            $table->string('Address')->nullable();
            $table->string('Photo')->nullable();
            $table->string('Position')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->string('CreateBy')->default('ADMIN');
            $table->timestamp('CreateDate')->useCurrent();
            $table->string('Operator')->default('ADMIN');
            $table->timestamp('TglEntry')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
