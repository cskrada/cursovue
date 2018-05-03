<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
    	'idclient',
    	'iduser',
    	'type_voucher',
    	'serie_voucher',
    	'num_voucher',
    	'fecha_hora',
    	'tax',
    	'total',
    	'status'
    ];
}
