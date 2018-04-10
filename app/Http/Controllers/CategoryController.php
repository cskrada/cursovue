<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    //
    public function index (Request $request){
    	$categories = Category::all();
    	return $categories;
    }

    public function store (Request $request){

    }

    public function update (Request $request){

    }

    public function desactivar (Request $request){

    }

    public function activar (Request $request){

    }
}
