<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idsale')->unsigned();
            $table->foreign('idsale')->references('id')->on('sales')->onDelete('cascade');
            $table->integer('idarticle')->unsigned();
            $table->foreign('idarticle')->references('id')->on('articles');

            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_detail');
    }
}
