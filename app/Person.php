<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	protected $table = 'persons';
    protected $fillable = [
    	'name','type_document','num_document','address','phone','email'
    ];

    public function provider()
    {
    	return $this->hasOne('App\Provider');
    }
}
