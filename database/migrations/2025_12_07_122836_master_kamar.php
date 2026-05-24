<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterKamar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masterkamar', function (Blueprint $table) {
            $table->string("code")->primary();
            $table->integer("harga")->nullable();
            $table->boolean("active")->default(true);
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
