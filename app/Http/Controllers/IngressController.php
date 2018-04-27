<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingress;

class IngressController extends Controller
{
    public function index (Request $request){
	    //if (!$request->ajax()) return redirect('/');

	    $buscar = $request->buscar;
	    $criterio = $request->criterio;

	    if ($buscar == ''){
	       $income = Ingress::join('persons','income.idprovider','=','persons.id')
            ->join('users','income.iduser','=','users.id')
            ->select('income.id','income.type_voucher','income.serie_voucher',
            'income.num_voucher','income.fecha_hora','income.tax','income.total',
            'income.status','persons.name','users.user')
            ->orderBy('income.id', 'desc')->paginate(3);
	    }
	    else{
	        $income = Ingress::join('persons','income.idprovider','=','persons.id')
            ->join('users','income.iduser','=','users.id')
            ->select('income.id','income.type_voucher','income.serie_voucher',
            'income.num_voucher','income.fecha_hora','income.tax','income.total',
            'income.status','persons.name','users.user')
	       	->where('income.'.$criterio, 'like', '%'. $buscar . '%')
	       	->orderBy('income.id', 'desc')->paginate(3);    
	    }

	    return [
	        'pagination' => [
	            'total'         => $income->total(),
	            'current_page'  => $income->currentPage(),
	            'per_page'      => $income->perPage(),
	            'last_page'     => $income->lastPage(),
	            'from'          => $income->firstItem(),
	            'to'            => $income->lastItem(),
	        ],
	        'income'        => $income
	    ];
    }

    public function store (Request $request){
        if (!$request->ajax()) return redirect('/');

        try{
        	DB::beginTransaction();
			$person = new Person();
        	$person->name = $request->name;
        	$person->type_document = $request->type_document;
        	$person->num_document = $request->num_document;
        	$person->address = $request->address;
        	$person->phone = $request->phone;
       	 	$person->email = $request->email;
       	 	$person->save();

       	 	$user = new User();
       	 	$user->user = $request->user;
       	 	$user->password = bcrypt($request->password);
       	 	$user->condition = '1';
       	 	$user->idrole = $request->idrole;

       	 	$user->id = $person->id;

       	 	$user->save();

       	 	DB::commit();

        }catch (Exception $e){
        	DB::rollBack();

        }      
    }

    public function update (Request $request){

        if (!$request->ajax()) return redirect('/');

        try{
        	DB::beginTransaction();

        	//Buscar primero el usuario a modificar
        	$user = User::findOrFail($request->id);

        	$person = Person::findOrFail($user->id);
			
        	$person->name = $request->name;
        	$person->type_document = $request->type_document;
        	$person->num_document = $request->num_document;
        	$person->address = $request->address;
        	$person->phone = $request->phone;
       	 	$person->email = $request->email;
       	 	$person->save();

       	 	$user->user = $request->user;
       	 	$user->password = bcrypt($request->password);
       	 	$user->condition = '1';
       	 	$user->idrole = $request->idrole;
       	 	$user->save();

       	 	DB::commit();

        }catch (Exception $e){
        	DB::rollBack();

        }
    }

    public function desactivar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
		$user->condition = '0';
		$user->save();
    }
}
