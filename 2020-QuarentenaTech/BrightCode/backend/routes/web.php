<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/sala', 'GameController@criarSala');


Route::post('/api/v1/criarSala', 'GameController@criarSala'); // Método POST - Sem parâmetros necessários.
Route::post('/api/v1/createGuest', 'GameController@criarGuest'); // PASSAR PARAMETRO NICK (NÃO OBRIGATÓRIO)
Route::get('/api/v1/entrarSala/{token}', 'GameController@entrarNaSala'); // MÉTODO GET PASSANDO O TOKEN DA SALA

