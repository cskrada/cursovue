<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingress extends Model
{
    protected $fillable = [
    	'idprovider',
    	'iduser',
    	'type_voucher',
    	'serie_voucher',
    	'num_voucher',
    	'fecha_hora',
    	'tax',
    	'total',
    	'status'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function provider()
    {
    	return $this->belongsTo('App\Provider');
    }
}
