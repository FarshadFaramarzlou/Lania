<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_baskets', function (Blueprint $table) {
            $table->integer('user_id')->primary()->unique();
            $table->text('basket')->nullable();
            $table->text('address')->nullable();
            $table->string('pay_type')->nullable();
            $table->integer('tahvil_type')->nullable();
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
        Schema::dropIfExists('bot_baskets');
    }
}
