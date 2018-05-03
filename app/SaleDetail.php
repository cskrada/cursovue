<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
	protected $fillable = 'sales_detail';
    protected $fillable = [
    	'idsale',
    	'idarticle',
    	'quantity',
    	'price',
    	'discount'
    ];
    public $timestamps = false;
}
