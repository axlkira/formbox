<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-setup', [\App\Http\Controllers\DatabaseConnectionController::class, 'showForm'])->name('db.setup');
Route::post('/db-setup', [\App\Http\Controllers\DatabaseConnectionController::class, 'testConnection'])->name('db.setup.post');

Route::get('/builder', [\App\Http\Controllers\BuilderController::class, 'index'])->name('builder.index');
