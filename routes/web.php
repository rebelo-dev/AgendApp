<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaController;



Route::get('/', function () {
    return view('welcome');
});


// Rota para verificar e enviar lembretes
Route::get('/verificar-lembretes', [ConsultaController::class, 'verificarEEnviarLembretes']);
Route::get('/marcar-consulta', function () {
    return view('marcar_consulta');
});


Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
