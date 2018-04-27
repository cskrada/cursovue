<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
	protected $table = 'income_detail';
    protected $fillable = [
    	'idingress',
    	'idarticle',
    	'quantity',
    	'price'
    ];
    public $timestamps = false;
}
