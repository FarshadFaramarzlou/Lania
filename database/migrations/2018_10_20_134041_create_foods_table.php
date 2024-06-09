<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('food_type_id')->default('0');
            $table->foreign('food_type_id')->references('id')->on('food_types')->onDelete('cascade');
            $table->string('img')->nullable();
            $table->string('imgTel')->nullable();
            $table->integer('price');
            $table->string('des')->nullable();
            $table->integer('sell_count')->default(0);
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
        Schema::dropIfExists('foods');
    }
}
