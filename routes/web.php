<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DbSetupController;
use App\Http\Controllers\TableSetupController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ImportTableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/builder', function () {
    return view('builder');
});

Route::get('/db-setup', [DbSetupController::class, 'showForm'])->name('db.setup');
Route::post('/db-setup', [DbSetupController::class, 'testConnection'])->name('db.setup.test');

Route::get('/table-setup', [TableSetupController::class, 'showForm'])->name('table.setup');
Route::post('/table-setup', [TableSetupController::class, 'save'])->name('table.setup.save');
Route::get('/table-setup/confirm', [TableSetupController::class, 'confirm'])->name('table.setup.confirm');
Route::post('/table-setup/create', [TableSetupController::class, 'create'])->name('table.setup.create');

Route::get('/formularios', [FormController::class, 'index'])->name('forms.index');
Route::get('/formularios/{id}/editar', [FormController::class, 'edit'])->name('forms.edit');
Route::get('/formularios/{id}', [FormController::class, 'show'])->name('forms.show');
Route::put('/formularios/{id}', [FormController::class, 'update'])->name('forms.update');
Route::delete('/formularios/{id}', [FormController::class, 'destroy'])->name('forms.destroy');

Route::get('/importar-tabla', [ImportTableController::class, 'index'])->name('import.tables.index');
Route::post('/importar-tabla', [ImportTableController::class, 'import'])->name('import.tables.import');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
