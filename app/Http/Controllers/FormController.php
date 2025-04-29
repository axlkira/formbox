<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::orderByDesc('id')->get();
        return view('forms_index', compact('forms'));
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        // Elimina los campos relacionados primero si hay relación
        if (method_exists($form, 'fields')) {
            $form->fields()->delete();
        }
        $form->delete();
        return redirect()->route('forms.index')->with('success', 'Formulario eliminado correctamente.');
    }

    public function edit($id)
    {
        $form = Form::findOrFail($id);
        return view('form_edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $form->name = $request->input('name');
        $form->save();
        return redirect()->route('forms.index')->with('success', 'Formulario actualizado correctamente.');
    }

    public function show($id)
    {
        $form = Form::findOrFail($id);
        $fields = method_exists($form, 'fields') ? $form->fields : [];
        return view('form_show', compact('form', 'fields'));
    }
}
