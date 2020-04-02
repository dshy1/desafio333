<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('v1/criarSala', 'GameController@criarSala'); // Método POST - Sem parâmetros necessários.
Route::post('v1/createGuest', 'GameController@criarGuest'); // PASSAR PARAMETRO NICK (NÃO OBRIGATÓRIO)
Route::get('v1/entrarSala/{token}', 'GameController@entrarNaSala'); // MÉTODO GET PASSANDO O TOKEN DA SALA
