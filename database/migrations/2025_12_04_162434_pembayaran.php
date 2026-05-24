<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string("code")->primary();
            $table->string("code_visitor")->nullable();
            $table->string("bukti_bayar")->nullable();
            $table->string("st_valid")->nullable();
            $table->string("create_by")->nullable();
            $table->string("approve_by")->nullable();
            $table->timestamp("create_date")->nullable();
            $table->timestamp("approve_date")->nullable();
            
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
