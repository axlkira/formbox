<?php
use Illuminate\Support\Facades\Route;

// Ruta de prueba para verificar que Laravel carga las rutas API
Route::get('/test-api', function() {
    return response()->json(['ok' => true, 'message' => 'API funcionando']);
});

use App\Http\Controllers\Api\FormApiController;

// Ruta para guardar formularios - No requiere autenticación para debugging
Route::post('/forms', [FormApiController::class, 'store']);

// Ruta de login API sin autenticación para obtener token
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/login', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }
    
    // Crear token para la API
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['token' => $token]);
});
