<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VideosModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->string("video_id")->primary();
            $table->string("url")->nullable();
            $table->string("grade")->nullable();
            $table->string("title")->nullable();
            $table->string("result")->nullable();
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
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
