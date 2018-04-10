<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','description','condition'];
    //fillable es para el usuario no escale permisos en el sistema, se asigna valor en masa
}
