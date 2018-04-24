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
        $this->validate($request, [
            'user'     => 'required|string',
            'password' => 'required|string'
        ]);
        // verifica si el usuario ingreso correctamente el usuario y la contraseña. Verifica la condicion del usuario es 1 para que pueda acceder
        if (Auth::attempt(['user' => $request->user,'password' => $request->password,'condition'=>1])){
            return redirect()->route('main');
        }

        return back();
    }
}
