<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';

    protected $fillable = [
    	'name','contact','phone_contact'
    ];

    public $timestamps = false;

    public function person()
    {
    	return $this->belongsTo('App\Person');
    }
}
