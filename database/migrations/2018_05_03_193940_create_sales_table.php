<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idclient')->unsigned();
            $table->foreign('idclient')->references('id')->on('persons');
            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('id')->on('users');

            $table->string('type_voucher', 20);
            $table->string('serie_voucher', 7)->nullable();
            $table->string('num_voucher', 10);
            $table->dateTime('fecha_hora');
            $table->decimal('tax', 4, 2);
            $table->decimal('total', 11, 2);
            $table->string('status', 20);

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
        Schema::dropIfExists('sales');
    }
}
