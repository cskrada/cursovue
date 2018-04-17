<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    // public function index (Request $request){
    //     if (!$request->ajax()) return redirect('/');

    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;

    //     if ($buscar == ''){
    // 	   $articles  =  Article::join('categories','articles.idcategory','=','categories.id')
    // 	   ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.price','articles.stock','articles.description','articles.condition')
    // 	   ->orderBy('id', 'desc')->paginate(3);
    //     }
    //     else{

    //     	$articles  =  Article::join('categories','articles.idcategory','=','categories.id')
    // 	   ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.price','articles.stock','articles.description','articles.condition')
    // 	   ->where('articles'.$criterio, 'like', '%'. $buscar . '%')
    // 	   ->orderBy('id', 'desc')->paginate(3);   
    //     }

    // 	return [
    //         'pagination' => [
    //             'total'         => $articles->total(),
    //             'current_page'  => $articles->currentPage(),
    //             'per_page'      => $articles->perPage(),
    //             'last_page'     => $articles->lastPage(),
    //             'from'          => $articles->firstItem(),
    //             'to'            => $articles->lastItem(),
    //         ],
    //         'articles'        => $articles
    //     ];
    // }

    public function index(Request $request){
    
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.price','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id', 'desc')->paginate(3);
        }
        else{
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.price','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articles.id', 'desc')->paginate(3);
        }
 
        return [
            'pagination' => [
                'total'        => $articles->total(),
                'current_page' => $articles->currentPage(),
                'per_page'     => $articles->perPage(),
                'last_page'    => $articles->lastPage(),
                'from'         => $articles->firstItem(),
                'to'           => $articles->lastItem(),
            ],
            'articles' => $articles
        ];
    }

    public function store (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $article = new Article();
    	$article->idcategory  = $request->idcategory;
    	$article->code        = $request->code;
    	$article->name        = $request->name;
    	$article->price       = $request->price;
    	$article->stock       = $request->stock;
    	$article->description = $request->description;
    	$article->condition   = '1';
    	$article->save();
    }

    public function update (Request $request){
    	//realiza una comprobacion antes de salvar los datos mediante field o find or fail
    	//el objeto va a ser igual a la clase categoria

    	if (!$request->ajax()) return redirect('/');

        $article = Article::findOrFail($request->id);
    	$article->idcategory  = $request->idcategory;
    	$article->code        = $request->code;
    	$article->name        = $request->name;
    	$article->price       = $request->price;
    	$article->stock 	  = $request->stock;
    	$article->description = $request->description;
    	$article->condition   = '1';
    	$article->save();
    }

    public function desactivar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
		$article->condition = '0';
		$article->save();
    }

    public function activar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
		$article->condition = '1';
		$article->save();
    }
}
