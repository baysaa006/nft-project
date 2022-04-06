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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('lname', 100)->nullable(true);
            $table->string('fname', 100)->nullable(true);
            $table->string('avatar', 300)->nullable(true);
            $table->string('nickname', 200);
            $table->string('email')->unique()->nullable(true);
            $table->string('password', 200)->nullable(true);
            $table->string('fb_id', 200)->nullable(true);
            $table->string('twitter_id', 200)->nullable(true);
            $table->string('google_id', 200)->nullable(true);
            $table->tinyInteger('status')->nullable(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
