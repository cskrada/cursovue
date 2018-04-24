<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Person;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index (Request $request){
	    if (!$request->ajax()) return redirect('/');

	    $buscar = $request->buscar;
	    $criterio = $request->criterio;

	    if ($buscar == ''){
	       $persons  =  User::join('persons', 'users.id','=','persons.id')
	       	->join('roles','users.idrole','=','roles.id')
	       	->select('persons.id','persons.name','persons.type_document','persons.num_document','persons.address','persons.phone','persons.email','users.user','users.password','users.condition','users.idrole','roles.name as rol')
	       	->orderBy('persons.id', 'desc')->paginate(3);
	    }
	    else{
	        $persons  =  User::join('persons', 'users.id','=','persons.id')
	       	->join('roles','users.idrole','=','roles.id')
	       	->select('persons.id','persons.name','persons.type_document','persons.num_document','persons.address','persons.phone','persons.email','users.user','users.password','users.condition','users.idrole','roles.name as rol')
	       	->where('persons.'.$criterio, 'like', '%'. $buscar . '%')
	       	->orderBy('persons.id', 'desc')->paginate(3);    
	    }

	    return [
	        'pagination' => [
	            'total'         => $persons->total(),
	            'current_page'  => $persons->currentPage(),
	            'per_page'      => $persons->perPage(),
	            'last_page'     => $persons->lastPage(),
	            'from'          => $persons->firstItem(),
	            'to'            => $persons->lastItem(),
	        ],
	        'persons'        => $persons
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

    public function activar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
		$user->condition = '1';
		$user->save();
    }
}
