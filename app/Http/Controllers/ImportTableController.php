<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use App\Models\FormField;

class ImportTableController extends Controller
{
    public function index()
    {
        \Log::info('ImportTableController@index ejecutado');
        // Listar tablas físicas que NO están en forms (compatible con MySQL y SQLite)
        $connection = DB::getDriverName();
        if ($connection === 'mysql') {
            $allTablesRaw = DB::select('SHOW TABLES');
            \Log::info('ALL TABLES RAW', ['allTablesRaw' => $allTablesRaw]);
            $key = null;
            if (!empty($allTablesRaw)) {
                $firstRow = (array)$allTablesRaw[0];
                $key = array_key_first($firstRow);
            }
            $allTables = $key ? array_map(fn($t) => $t->$key, $allTablesRaw) : [];
        } else {
            // SQLite
            $allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $allTables = array_map(fn($t) => $t->name, $allTables);
        }
        $formTables = Form::pluck('table_name')->toArray();
        \Log::info('FORM TABLES', ['formTables' => $formTables]);
        $tablesToImport = array_diff($allTables, $formTables);
        // Solo mostrar tablas que no están en forms y no son del sistema
        $excluded = [
            'migrations', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs', 'cache', 'job_batches', 'sessions', 'form_fields', 'forms', 'db_connections', 'sqlite_sequence', 'cache_locks', 'jobs', 'users'
        ];
        $tablesToImport = array_filter($tablesToImport, function($t) use ($excluded) {
            return !in_array($t, $excluded);
        });
        $tablesToImport = array_values($tablesToImport); // ¡Forzar array indexado!
        \Log::info('TABLES TO IMPORT', ['tablesToImport' => $tablesToImport]);
        // DEBUG: Si tp1 existe, forzar que aparezca
        if (in_array('tp1', $allTables) && !in_array('tp1', $formTables)) {
            $tablesToImport[] = 'tp1';
        }
        
        // DEBUG: Mostrar siempre $tablesToImport en el log para saber qué detecta el backend
        \Log::info('Tablas físicas detectadas para importar', ['all_tables' => $allTables, 'form_tables' => $formTables, 'tables_to_import' => $tablesToImport]);
        
        return view('import_tables', [
            'tablesToImport' => $tablesToImport,
            'allTables' => $allTables,
            'formTables' => $formTables
        ]);
    }

    public function import(Request $request)
    {
        $table = $request->input('table');
        if (!$table) {
            return back()->with('error', 'Selecciona una tabla para importar.');
        }
        // Revisar que no exista ya en forms
        if (Form::where('table_name', $table)->exists()) {
            return back()->with('error', 'Esta tabla ya tiene un formulario asociado.');
        }
        // Obtener columnas y tipos según el motor de base de datos
        $connection = DB::getDriverName();
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        $types = [];
        if ($connection === 'mysql') {
            $rawColumns = DB::select("SHOW COLUMNS FROM `$table`");
            foreach ($rawColumns as $col) {
                $types[$col->Field] = $col->Type;
            }
        } else {
            $rawColumns = DB::select(DB::raw("PRAGMA table_info($table)"));
            foreach ($rawColumns as $col) {
                $types[$col->name] = $col->type;
            }
        }
        // Crear formulario
        $form = Form::create([
            'name' => $table,
            'table_name' => $table,
        ]);
        foreach ($columns as $i => $col) {
            $type = $types[$col] ?? 'string';
            FormField::create([
                'form_id' => $form->id,
                'name' => $col,
                'label' => $col,
                'type' => $type,
                'required' => false,
                'order' => $i,
            ]);
        }
        return redirect()->route('forms.index')->with('success', '¡Tabla importada como formulario!');
    }
}
