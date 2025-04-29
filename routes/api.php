<?php
use Illuminate\Support\Facades\Route;

// Ruta de prueba para verificar que Laravel carga las rutas API
Route::get('/test-api', function() {
    return response()->json(['ok' => true, 'message' => 'API funcionando']);
});

use App\Http\Controllers\Api\FormApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/forms', [FormApiController::class, 'store']);
    // Aquí puedes agregar más endpoints en el futuro (listar, editar, eliminar)
});
