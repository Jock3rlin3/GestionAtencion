<?php

use App\Http\Controllers\ConsultaAtencionController;
use App\Http\Controllers\TratamientoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tratamientos');

Route::resource('tratamientos', TratamientoController::class)->except(['show']);
Route::resource('consultas', ConsultaAtencionController::class)->except(['show']);
