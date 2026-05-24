<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Visitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor', function (Blueprint $table) {
            $table->string("qr_code")->primary();
            $table->string("qr_ticket")->nullable();
            $table->string("full_name")->nullable();
            $table->string("place_of_birth")->nullable();
            $table->string("birth_date")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("gender")->nullable();
            $table->string("mesengger")->nullable();
            $table->string("status_mesengger")->nullable();
            $table->string("status_mesengger_other")->nullable();
            $table->string("position")->nullable();
            $table->string("office_address")->nullable();
            $table->string("account_number")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("account_bank_name")->nullable();
            $table->string("signature")->nullable();
            $table->string("size_jersey")->nullable();
            $table->timestamp("created_at")->nullable();
            $table->timestamp("updated_at")->nullable();

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
