<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Category;

class CategoryController extends Controller
{
    //
    public function index (Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == ''){
    	   $articles  =  Article::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $articles = Article::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);    
        }

    	return [
            'pagination' => [
                'total'         => $articles->total(),
                'current_page'  => $articles->currentPage(),
                'per_page'      => $articles->perPage(),
                'last_page'     => $articles->lastPage(),
                'from'          => $articles->firstItem(),
                'to'            => $articles->lastItem(),
            ],
            'articles'        => $articles
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
