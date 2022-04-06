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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string("title", 500)->nullable(true);
            $table->mediumText("content_text")->nullable(true);
            $table->string("content_image", 200)->nullable(true);
            $table->string("nft_id", 500)->nullable(true);
            $table->tinyInteger("status");
            $table->tinyInteger("is_nft");
            $table->bigInteger("like_count");
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('posts');
    }
};
