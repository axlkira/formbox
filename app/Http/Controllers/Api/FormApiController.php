<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormApiController extends Controller
{
    // Guarda un formulario y sus campos
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'table_name' => 'required|string|max:255|unique:forms,table_name',
            'fields' => 'required|array|min:1',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.name' => 'required|string',
            'fields.*.options' => 'nullable|array',
            'fields.*.validation_prompt' => 'nullable|string',
            'fields.*.order' => 'nullable|integer',
        ]);
        if ($validated->fails()) {
            return response()->json(['status'=>'error','errors'=>$validated->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $form = Form::create([
                'name' => $request->name,
                'table_name' => $request->table_name,
                'user_id' => $request->user() ? $request->user()->id : null,
            ]);
            foreach ($request->fields as $i => $field) {
                $form->fields()->create([
                    'type' => $field['type'],
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'options' => $field['options'] ?? null,
                    'validation_prompt' => $field['validation_prompt'] ?? null,
                    'order' => $field['order'] ?? $i,
                ]);
            }
            DB::commit();
            return response()->json(['status'=>'success','form_id'=>$form->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'=>'error','message'=>$e->getMessage()], 500);
        }
    }
}
