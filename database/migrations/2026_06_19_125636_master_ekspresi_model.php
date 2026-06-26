<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterEkspresiModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_expression', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("expression")->nullable();
            $table->integer("stars")->nullable();
            $table->integer("min_value")->nullable();
            $table->integer("max_value")->nullable();
            $table->string("solution")->nullable();
            $table->boolean("active")->default(true);
            $table->string("image")->nullable();
            $table->string("create_by")->default('SA');
            $table->string("update_by")->default('SA');
            $table->timestamp("create_at")->useCurrent();
            $table->timestamp("update_at")->useCurrent()->useCurrentOnUpdate();
            
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
