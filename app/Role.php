<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'Roles';

    protected $fillable = [
    	'name','description','condition'
    ];
    public $timestamps = false;

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
