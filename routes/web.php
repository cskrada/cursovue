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

Route::get('/', function () {
    return view('contenido/contenido');
});

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

//Rutas para Cliente
Route::get('/client', 'ClientController@index');
Route::post('/client/registrar', 'ClientController@store');
Route::put('/client/actualizar', 'ClientController@update');

