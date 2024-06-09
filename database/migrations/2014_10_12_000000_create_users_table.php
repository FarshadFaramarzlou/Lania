<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(/**
         * @param Blueprint $table
         */
            'users', function (Blueprint $table) {
            $table->increments('id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('type_id')->default(3);
            $table->foreign('type_id')->references('id')->on('user_types')->onDelete('cascade');
            $table->integer('chat_id')->nullable();
            $table->foreign('chat_id')->references('user_id')->on('bot_baskets')->onDelete('cascade');
            $table->string('avatar')->nullable();
            $table->string('phone',11)->unique();
            //$table->timestamp('phone_verified_at')->nullable();
            $table->string('code')->nullable();
            $table->boolean('active')->default(0);
            $table->string('password');
            $table->string('address')->nullable();
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
}
