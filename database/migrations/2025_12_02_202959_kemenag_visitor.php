<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KemenagVisitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kemenag_visitor', function (Blueprint $table) {
            $table->string("code")->primary();
            $table->string("ticket")->nullable();
            $table->string("full_name")->nullable();
            $table->string("nip")->nullable();
            $table->string("npwp")->nullable();
            $table->string("place_of_birth")->nullable();
            $table->string("birth_date")->nullable();
            $table->string("gender")->nullable();
            $table->string("organization")->nullable();
            $table->string("satker")->nullable();
            $table->string("grade")->nullable();
            $table->string("role")->nullable();
            $table->string("email")->nullable();
            $table->string("address")->nullable();
            $table->string("office_address")->nullable();
            $table->string("phone")->nullable();
            $table->string("password")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("bank_number")->nullable();
            $table->string("bank_account_name")->nullable();
            $table->string("paper_work")->nullable();
            $table->string("foto")->nullable();
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
