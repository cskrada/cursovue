<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Provider; 
use App\Person;

class ProviderController extends Controller
{
    public function index (Request $request){
	    if (!$request->ajax()) return redirect('/');

	    $buscar = $request->buscar;
	    $criterio = $request->criterio;

	    if ($buscar == ''){
	       $persons  =  Provider::join('persons', 'providers.id','=','persons.id')
	       	->select('persons.id','persons.name','persons.type_document','persons.num_document','persons.address','persons.phone','persons.email','providers.contact','providers.phone_contact')
	       	->orderBy('persons.id', 'desc')->paginate(3);
	    }
	    else{
	        $persons = Provider::join('persons', 'providers.id','=','persons.id')
	       	->select('persons.id','persons.name','persons.type_document','persons.num_document','persons.address','persons.phone','persons.email','providers.contact','providers.phone_contact')
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

       	 	$provider = new Provider();
       	 	$provider->contact = $request->contact;
       	 	$provider->phone_contact = $request->phone_contact;
       	 	$provider->id = $person->id;
       	 	$provider->save();

       	 	DB::commit();

        }catch (Exception $e){
        	DB::rollBack();

        }      
    }

    public function update (Request $request){

        if (!$request->ajax()) return redirect('/');

        try{
        	DB::beginTransaction();

        	//Buscar primero el proveedor a modificar
        	$provider = Provider::findOrFail($request->id);

        	$person = Person::findOrFail($provider->id);
			
        	$person->name = $request->name;
        	$person->type_document = $request->type_document;
        	$person->num_document = $request->num_document;
        	$person->address = $request->address;
        	$person->phone = $request->phone;
       	 	$person->email = $request->email;
       	 	$person->save();

       	 	$provider->contact = $request->contact;
       	 	$provider->phone_contact = $request->phone_contact;
       	 	$provider->id = $person->id;
       	 	$provider->save();

       	 	DB::commit();

        }catch (Exception $e){
        	DB::rollBack();

        }
    }
}
