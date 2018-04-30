<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

	Route::group(['middleware'=>['Almacenero']],function(){
		//rutas para Categoria
		Route::get('/category', 'CategoryController@index');
		Route::post('/category/registrar', 'CategoryController@store');
		Route::put('/category/actualizar', 'CategoryController@update');
		Route::put('/category/desactivar', 'CategoryController@desactivar');
		Route::put('/category/activar', 'CategoryController@activar');
		Route::get('/category/selectCategory', 'CategoryController@selectCategory');

		//Rutas para Articulos
		Route::get('/article', 'ArticleController@index');
		Route::post('/article/registrar', 'ArticleController@store');
		Route::put('/article/actualizar', 'ArticleController@update');
		Route::put('/article/desactivar', 'ArticleController@desactivar');
		Route::put('/article/activar', 'ArticleController@activar');

		//Rutas para Proveedor
		Route::get('/provider', 'ProviderController@index');
		Route::post('/provider/registrar', 'ProviderController@store');
		Route::put('/provider/actualizar', 'ProviderController@update');
		Route::get('/provider/selectProvider', 'ProviderController@selectProvider');

		Route::get('/ingress', 'IngressController@index');
		Route::post('/ingress/registrar', 'IngressController@store');
		Route::put('/ingress/desactivar', 'IngressController@desactivar');
	});

	Route::group(['middleware'=>['Vendedor']],function(){
				
		//Rutas para Cliente
		Route::get('/client', 'ClientController@index');
		Route::post('/client/registrar', 'ClientController@store');
		Route::put('/client/actualizar', 'ClientController@update');
	});	

	Route::group(['middleware'=>['Administrador']],function(){

		//rutas para Categoria
		Route::get('/category', 'CategoryController@index');
		Route::post('/category/registrar', 'CategoryController@store');
		Route::put('/category/actualizar', 'CategoryController@update');
		Route::put('/category/desactivar', 'CategoryController@desactivar');
		Route::put('/category/activar', 'CategoryController@activar');
		Route::get('/category/selectCategory', 'CategoryController@selectCategory');

		//Rutas para Articulos
		Route::get('/article', 'ArticleController@index');
		Route::post('/article/registrar', 'ArticleController@store');
		Route::put('/article/actualizar', 'ArticleController@update');
		Route::put('/article/desactivar', 'ArticleController@desactivar');
		Route::put('/article/activar', 'ArticleController@activar');

		//Rutas para Proveedor
		Route::get('/provider', 'ProviderController@index');
		Route::post('/provider/registrar', 'ProviderController@store');
		Route::put('/provider/actualizar', 'ProviderController@update');
		Route::get('/provider/selectProvider', 'ProviderController@selectProvider');

		//Rutas para Ingreso
		Route::get('/ingress', 'IngressController@index');
		Route::post('/ingress/registrar', 'IngressController@store');
		Route::put('/ingress/desactivar', 'IngressController@desactivar');

		//Rutas para Cliente
		Route::get('/client', 'ClientController@index');
		Route::post('/client/registrar', 'ClientController@store');
		Route::put('/client/actualizar', 'ClientController@update');

		//Rutas para Los Roles
		Route::get('/role', 'RoleController@index');
		Route::get('/role/selectRole', 'RoleController@selectRole');

		//Rutas para Los Usuarios
		Route::get('/user', 'UserController@index');
		Route::post('/user/registrar', 'UserController@store');
		Route::put('/user/actualizar', 'UserController@update');
		Route::put('/user/desactivar', 'UserController@desactivar');
		Route::put('/user/activar', 'UserController@activar');
	});
		
});

// Route::get('/home', 'HomeController@index')->name('home');
