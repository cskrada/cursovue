<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = [
    	'idcategory','code','name','price','stock','description','condition'
    ];

    //la tabla articulos esta relacionado con la tabla de categorias. la relacion inversa donde se tomaremos la funcion belongsTo. hacemos referencia la modelo de categoria

    public function category(){
    	return $this->belongsTo('App\Category');
    }
}
