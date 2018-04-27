<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idingress')->unsigned();
            $table->foreign('idingress')->references('id')->on('income')->onDelete('cascade');
            $table->integer('idarticle')->unsigned();
            $table->foreign('idarticle')->references('id')->on('articles');
            $table->integer('quantity');
            $table->decimal('price', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_detail');
    }
}
