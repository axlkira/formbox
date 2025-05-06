<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Form;
use App\Models\FormField;
use App\Services\TableCreatorService;

class TableSetupController extends Controller
{
    public function showForm()
    {
        return view('table_setup');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_name' => 'required|string|alpha_dash',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|alpha_dash',
            'fields.*.type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Guardar en sesión para el siguiente paso (creación de tabla)
        Session::put('table_setup', [
            'table_name' => $request->table_name,
            'fields' => $request->fields
        ]);
        // Redirigir SIEMPRE a la confirmación
        return redirect()->route('table.setup.confirm')->with('success', 'Definición guardada. Revisa y confirma la creación de la tabla.');
    }

    public function confirm()
    {
        $setup = Session::get('table_setup');
        if (!$setup) {
            return redirect()->route('table.setup')->withErrors(['table_name' => 'Debes definir la tabla primero.']);
        }
        return view('table_setup_confirm', ['setup' => $setup]);
    }

    public function create(Request $request)
    {
        $setup = Session::get('table_setup');
        if (!$setup) {
            return redirect()->route('table.setup')->withErrors(['table_name' => 'Debes definir la tabla primero.']);
        }
        $table = $setup['table_name'];
        $fields = $setup['fields'];
        try {
            if (Schema::hasTable($table)) {
                return redirect()->back()->with('error', 'La tabla ya existe en la base de datos.');
            }
            // Usar el nuevo servicio para crear la tabla física avanzada
            $service = new TableCreatorService();
            $service->createTable($table, $fields);
            // Guardar definición lógica (forms y form_fields)
            $form = Form::create([
                'name' => $table, // Por ahora igual al nombre de la tabla
                'table_name' => $table,
            ]);
            foreach ($fields as $i => $field) {
                FormField::create([
                    'form_id' => $form->id,
                    'name' => $field['name'],
                    'label' => $field['label'] ?? $field['name'],
                    'type' => $field['type'],
                    'required' => !empty($field['required']),
                    'order' => $i,
                    'extra' => json_encode($field),
                ]);
            }
            Session::forget('table_setup');
            return redirect()->route('table.setup.confirm')->with('success', '¡Tabla y formulario creados exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('table.setup.confirm')->with('error', 'Error al crear la tabla o el formulario: ' . $e->getMessage());
        }
    }
}
