<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class DbSetupController extends Controller
{
    public function showForm()
    {
        return view('db_setup');
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|numeric',
            'database' => 'required|string',
            'username' => 'required|string',
            // password is optional
        ]);

        $config = [
            'driver' => 'mysql',
            'host' => $request->host,
            'port' => $request->port,
            'database' => $request->database,
            'username' => $request->username,
            'password' => $request->password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        try {
            DB::purge('dynamic');
            config(['database.connections.dynamic' => $config]);
            DB::connection('dynamic')->getPdo();
            // Opcional: guardar en sesión encriptado
            Session::put('db_config', Crypt::encrypt($config));
            return redirect()->back()->with('success', '¡Conexión exitosa!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['db' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }
}
