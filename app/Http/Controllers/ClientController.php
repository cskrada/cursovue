<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class ClientController extends Controller
{
    public function index (Request $request){
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == ''){
           $persons  =  Person::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $persons = Person::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);    
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
        $person = new Person();
        $person->name = $request->name;
        $person->type_document = $request->type_document;
        $person->num_document = $request->num_document;
        $person->address = $request->address;
        $person->phone = $request->phone;
        $person->email = $request->email;
        $person->save();
    }

    public function update (Request $request){
        //realiza una comprobacion antes de salvar los datos mediante field o find or fail
        //el objeto va a ser igual a la clase persona
        if (!$request->ajax()) return redirect('/');
        $person = Person::findOrFail($request->id);
        $person->name = $request->name;
        $person->type_document = $request->type_document;
        $person->num_document = $request->num_document;
        $person->address = $request->address;
        $person->phone = $request->phone;
        $person->email = $request->email;
        $person->save();
    }
}
