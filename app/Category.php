<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //fillable es para el usuario no escale permisos en el sistema, se asigna valor en masa
	//protected $table = 'categories'
	//protected $primaryKey= 'id'

    protected $fillable = ['name','description','condition'];

    //como es una relacion de uno a muchos el nombre de la funcion debe ser plural y llamamos al modelo de articulo

    public function articles()
    {
    	return $this->hasMany('App\Article');
    }
}
