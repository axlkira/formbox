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
            // 1. Crear tabla física
            Schema::create($table, function (Blueprint $tableBlueprint) use ($fields) {
                $tableBlueprint->id();
                foreach ($fields as $field) {
                    $type = $field['type'];
                    $name = $field['name'];
                    $required = isset($field['required']);
                    switch ($type) {
                        case 'string':
                            $col = $tableBlueprint->string($name);
                            break;
                        case 'integer':
                            $col = $tableBlueprint->integer($name);
                            break;
                        case 'boolean':
                            $col = $tableBlueprint->boolean($name);
                            break;
                        case 'text':
                            $col = $tableBlueprint->text($name);
                            break;
                        case 'date':
                            $col = $tableBlueprint->date($name);
                            break;
                        default:
                            $col = $tableBlueprint->string($name);
                    }
                    if ($required) {
                        $col->nullable(false);
                    } else {
                        $col->nullable();
                    }
                }
                $tableBlueprint->timestamps();
            });
            // 2. Guardar definición lógica (forms y form_fields)
            $form = Form::create([
                'name' => $table, // Por ahora igual al nombre de la tabla
                'table_name' => $table,
            ]);
            foreach ($fields as $i => $field) {
                FormField::create([
                    'form_id' => $form->id,
                    'name' => $field['name'],
                    'label' => $field['name'], // Por ahora igual al nombre
                    'type' => $field['type'],
                    'required' => isset($field['required']),
                    'order' => $i,
                ]);
            }
            Session::forget('table_setup');
            return redirect()->route('table.setup.confirm')->with('success', '¡Tabla y formulario creados exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('table.setup.confirm')->with('error', 'Error al crear la tabla o el formulario: ' . $e->getMessage());
        }
    }
}
