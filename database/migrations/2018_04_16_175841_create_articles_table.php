<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *id category: es la clave foranea y se le indica unsigned para este caso
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategory')->unsigned();
            $table->string('code',50)->nullable();
            $table->string('name',100)->unique();
            $table->decimal('price',11,2);
            $table->integer('stock');
            $table->string('description',256)->nullable();
            $table->boolean('condition')->default(1);
            $table->timestamps();

            $table->foreign('idcategory')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
