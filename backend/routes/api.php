<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// route::get('/users', [UserController::class, 'index']); //aqui cadastrariamos cada rota 1 por 1
// route::post('/users', [UserController::class, 'store']);

Route::apiResource('/users', UserController::class); //cadastra as 5 rotas de forma automatica

