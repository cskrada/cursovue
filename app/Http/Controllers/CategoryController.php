<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Category;

class CategoryController extends Controller
{
    //
    public function index (Request $request){
        // if (!$request->ajax()) return redirect('/');
    	$categories =  Category::paginate(2);
    	return [
        'pagination' => [
            'total'         => $categories->total(),
            'current_page'  => $categories->current_page(),
            'per_page'      => $categories->per_page(),
            'last_page'     => $categories->last_page(),
            'from'          => $categories->from(),
            'to'            => $categories->to(),
        ],
        'categories'        => $categories
    ];
    }

    public function store (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $category = new Category();
    	$category->name = $request->name;
    	$category->description = $request->description;
    	$category->condition = '1';
    	$category->save();
    }

    public function update (Request $request){
    	//realiza una comprobacion antes de salvar los datos mediante field o find or fail
    	//el objeto va a ser igual a la clase categoria
    	if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
    	$category->name = $request->name;
    	$category->description = $request->description;
    	$category->condition = '1';
    	$category->save();
    }

    public function desactivar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
		$category->condition = '0';
		$category->save();
    }

    public function activar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
		$category->condition = '1';
		$category->save();
    }
}
