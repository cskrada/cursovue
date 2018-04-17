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
    	   $categories  =  Category::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $categories = Category::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);    
        }

    	return [
            'pagination' => [
                'total'         => $categories->total(),
                'current_page'  => $categories->currentPage(),
                'per_page'      => $categories->perPage(),
                'last_page'     => $categories->lastPage(),
                'from'          => $categories->firstItem(),
                'to'            => $categories->lastItem(),
            ],
            'categories'        => $categories
        ];
    }

    public function selectCategory(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categories = Category::where('condition','=','1')
        ->select('id','name')->orderBy('name','asc')->get();
        return ['categories' => $categories];
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
