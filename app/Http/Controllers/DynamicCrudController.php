<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Form;
use App\Models\FormField;

class DynamicCrudController extends Controller
{
    // Listar registros
    public function index($form)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        $records = DB::table($form)->paginate(15);
        return view('dynamic_crud.index', compact('form', 'formModel', 'fields', 'records'));
    }

    // Mostrar formulario de creación
    public function create($form)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        return view('dynamic_crud.create', compact('form', 'formModel', 'fields'));
    }

    // Guardar nuevo registro
    public function store(Request $request, $form)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        $data = $request->only($fields->pluck('name')->toArray());
        DB::table($form)->insert($data);
        return redirect()->route('dynamic-crud.index', $form)->with('success', 'Registro creado.');
    }

    // Mostrar formulario de edición
    public function edit($form, $id)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        $record = DB::table($form)->where('id', $id)->first();
        return view('dynamic_crud.edit', compact('form', 'formModel', 'fields', 'record'));
    }

    // Actualizar registro
    public function update(Request $request, $form, $id)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        $data = $request->only($fields->pluck('name')->toArray());
        DB::table($form)->where('id', $id)->update($data);
        return redirect()->route('dynamic-crud.index', $form)->with('success', 'Registro actualizado.');
    }

    // Eliminar registro
    public function destroy($form, $id)
    {
        DB::table($form)->where('id', $id)->delete();
        return redirect()->route('dynamic-crud.index', $form)->with('success', 'Registro eliminado.');
    }

    // Mostrar registro (show)
    public function show($form, $id)
    {
        $formModel = Form::where('table_name', $form)->firstOrFail();
        $fields = FormField::where('form_id', $formModel->id)->orderBy('order')->get();
        $record = DB::table($form)->where('id', $id)->first();
        return view('dynamic_crud.show', compact('form', 'formModel', 'fields', 'record'));
    }
}
