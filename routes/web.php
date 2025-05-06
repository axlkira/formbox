<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DbSetupController;
use App\Http\Controllers\TableSetupController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ImportTableController;
use App\Http\Controllers\FormboxController;
use App\Http\Controllers\DynamicCrudController;
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

// Descarga .blade.php generado
Route::post('/formbox/download-blade', [FormboxController::class, 'downloadBlade']);
// Descarga HTML generado
Route::post('/formbox/download-html', [FormboxController::class, 'downloadHtml']);
// Descargar (exportar) formulario guardado (descarga directa)
Route::get('/formbox/download-json/{filename}', [App\Http\Controllers\FormboxController::class, 'downloadJson'])->name('formbox.download-json');
// Guardar formulario desde builder (AJAX)
Route::post('/formbox/save', [App\Http\Controllers\FormboxController::class, 'save'])->name('formbox.save');
// Listar formularios guardados (AJAX)
Route::get('/formbox/list-json', [App\Http\Controllers\FormboxController::class, 'listJson'])->name('formbox.list-json');
// Cargar formulario guardado (AJAX)
Route::get('/formbox/load-json/{filename}', [App\Http\Controllers\FormboxController::class, 'loadJson'])->name('formbox.load-json');
// Renombrar formulario guardado (AJAX)
Route::post('/formbox/rename-json/{filename}', [App\Http\Controllers\FormboxController::class, 'renameJson'])->name('formbox.rename-json');
// Eliminar formulario guardado (AJAX)
Route::delete('/formbox/delete-json/{filename}', [App\Http\Controllers\FormboxController::class, 'deleteJson'])->name('formbox.delete-json');
// Importar formulario guardado (AJAX, subida de archivo)
Route::post('/formbox/import-json', [App\Http\Controllers\FormboxController::class, 'importJson'])->name('formbox.import-json');

// CRUD dinámico para cualquier formulario generado
Route::prefix('forms/{form}/records')->group(function () {
    Route::get('/', [DynamicCrudController::class, 'index'])->name('dynamic-crud.index');
    Route::get('/create', [DynamicCrudController::class, 'create'])->name('dynamic-crud.create');
    Route::post('/', [DynamicCrudController::class, 'store'])->name('dynamic-crud.store');
    Route::get('/{id}', [DynamicCrudController::class, 'show'])->name('dynamic-crud.show');
    Route::get('/{id}/edit', [DynamicCrudController::class, 'edit'])->name('dynamic-crud.edit');
    Route::put('/{id}', [DynamicCrudController::class, 'update'])->name('dynamic-crud.update');
    Route::delete('/{id}', [DynamicCrudController::class, 'destroy'])->name('dynamic-crud.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
