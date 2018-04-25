<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 
    public function showLoginForm(){
        return view('auth.login');
    }

    //valida la peticion de inicio del usuario
    public function login(Request $request){
        $this->validateLogin($request);
        
        // verifica si el usuario ingreso correctamente el usuario y la contraseña. Verifica la condicion del usuario es 1 para que pueda acceder
        if (Auth::attempt(['user' => $request->user,'password' => $request->password,'condition'=>1])){
            return redirect()->route('main');
        }

        //con el metodo withErrors sirve para mostrar los errores pasando los parametros en el cual queremos reflejar en el identificador que en este caso es 'user' donde el mensaje que va a mostrar esta dentro de la carpeta resources/lang/en/auth.php
        return back()
        ->withErrors(['user'=> trans('auth.failed')])
        ->withInput(request(['user']));
    }

    protected function validateLogin(Request $request){
        $this->validate($request, [
            'user'     => 'required|string',
            'password' => 'required|string'
        ]);
    }
}
