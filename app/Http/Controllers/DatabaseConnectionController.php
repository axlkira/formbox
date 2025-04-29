<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionController extends Controller
{
    public function showForm()
    {
        return view('db_setup');
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required|numeric',
            'database' => 'required',
            'username' => 'required',
            'password' => 'nullable',
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

        Config::set('database.connections.dynamic', $config);

        try {
            DB::connection('dynamic')->getPdo();
            Session::put('db_connection', $config);
            return redirect()->route('builder.index')->with('success', '¡Conexión exitosa!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['db' => 'Error de conexión: ' . $e->getMessage()]);
        }
    }
}
