@extends('layouts.builder')

@section('title', 'Configurar Tabla')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background:transparent;">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 pb-0 d-flex align-items-center">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-gear me-2"></i>Configurar Tabla</h3>
                <hr class="mt-2 mb-0 w-100" style="border-color:#0d6efd;opacity:.2;">
            </div>
            <div class="card-body bg-white">
                <form method="POST" action="{{ route('table.setup.save') }}" id="table-setup-form">
                    @csrf
                    <div class="mb-4">
                        <label for="table_name" class="form-label fw-semibold text-secondary">Nombre de la tabla</label>
                        <input id="table_name" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="text" name="table_name" value="{{ old('table_name') }}" required placeholder="ejemplo_formulario" autofocus />
                        @error('table_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Campos de la tabla</label>
                        <div id="fields-area">
                            <!-- Campos dinámicos -->
                            @if(old('fields'))
                                @foreach(old('fields') as $i => $field)
                                    <div class="row g-2 mb-2 align-items-center">
                                        <div class="col-md-5">
                                            <input name="fields[{{$i}}][name]" class="form-control bg-light border-2 border-primary-subtle rounded-3 shadow-sm field-name" placeholder="Nombre (sin espacios)" pattern="^[a-zA-Z0-9_]+$" title="Solo letras, números y guion bajo" value="{{$field['name']}}" required />
                                            <div class="invalid-feedback d-none">Solo letras, números y guion bajo (sin espacios)</div>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="fields[{{$i}}][type]" class="form-select bg-light border-2 border-primary-subtle rounded-3 shadow-sm">
                                                <option value="string" @if($field['type']==='string') selected @endif>Texto</option>
                                                <option value="integer" @if($field['type']==='integer') selected @endif>Número</option>
                                                <option value="boolean" @if($field['type']==='boolean') selected @endif>Switch</option>
                                                <option value="text" @if($field['type']==='text') selected @endif>Textarea</option>
                                                <option value="date" @if($field['type']==='date') selected @endif>Fecha</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="fields[{{$i}}][is_primary]" class="form-check-input" id="primary{{$i}}" @if(isset($field['is_primary'])) checked @endif>
                                                <label class="form-check-label" for="primary{{$i}}">Llave primaria</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="fields[{{$i}}][is_foreign]" class="form-check-input foreign-check" data-index="{{$i}}" id="foreign{{$i}}" @if(isset($field['is_foreign'])) checked @endif>
                                                <label class="form-check-label" for="foreign{{$i}}">Llave foránea</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 foreign-fields" id="foreign-fields-{{$i}}" style="display:@if(isset($field['is_foreign']))block;@else none;@endif">
                                            <input name="fields[{{$i}}][foreign_table]" class="form-control mb-1" placeholder="Tabla destino" value="{{$field['foreign_table'] ?? ''}}" />
                                            <input name="fields[{{$i}}][foreign_column]" class="form-control" placeholder="Columna destino" value="{{$field['foreign_column'] ?? ''}}" />
                                            <select name="fields[{{$i}}][on_delete]" class="form-select mt-1">
                                                <option value="cascade" @if(($field['on_delete'] ?? '')==='cascade') selected @endif>Cascade</option>
                                                <option value="restrict" @if(($field['on_delete'] ?? '')==='restrict') selected @endif>Restrict</option>
                                                <option value="set null" @if(($field['on_delete'] ?? '')==='set null') selected @endif>Set Null</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row g-2 mb-2 align-items-center">
                                    <div class="col-md-5">
                                        <input name="fields[0][name]" class="form-control bg-light border-2 border-primary-subtle rounded-3 shadow-sm field-name" placeholder="Nombre (sin espacios)" pattern="^[a-zA-Z0-9_]+$" title="Solo letras, números y guion bajo" required />
                                        <div class="invalid-feedback d-none">Solo letras, números y guion bajo (sin espacios)</div>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="fields[0][type]" class="form-select bg-light border-2 border-primary-subtle rounded-3 shadow-sm">
                                            <option value="string">Texto</option>
                                            <option value="integer">Número</option>
                                            <option value="boolean">Switch</option>
                                            <option value="text">Textarea</option>
                                            <option value="date">Fecha</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="fields[0][is_primary]" class="form-check-input" id="primary0">
                                            <label class="form-check-label" for="primary0">Llave primaria</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="fields[0][is_foreign]" class="form-check-input foreign-check" data-index="0" id="foreign0">
                                            <label class="form-check-label" for="foreign0">Llave foránea</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 foreign-fields" id="foreign-fields-0" style="display:none">
                                        <input name="fields[0][foreign_table]" class="form-control mb-1" placeholder="Tabla destino" />
                                        <input name="fields[0][foreign_column]" class="form-control" placeholder="Columna destino" />
                                        <select name="fields[0][on_delete]" class="form-select mt-1">
                                            <option value="cascade">Cascade</option>
                                            <option value="restrict">Restrict</option>
                                            <option value="set null">Set Null</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-field" class="btn btn-primary btn-sm mt-2"><i class="bi bi-plus-circle"></i> Agregar campo</button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-save me-1"></i>Guardar y crear tabla</button>
                    </div>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let fieldIndex = document.querySelectorAll('#fields-area .row').length;
                        document.querySelectorAll('.foreign-check').forEach(function(chk){
                            chk.addEventListener('change', function(){
                                let idx = this.getAttribute('data-index');
                                let foreignDiv = document.getElementById('foreign-fields-'+idx);
                                if(this.checked){
                                    foreignDiv.style.display = 'block';
                                } else {
                                    foreignDiv.style.display = 'none';
                                }
                            });
                        });
                        document.getElementById('add-field').addEventListener('click', function() {
                            setTimeout(function() {
                                let idx = fieldIndex;
                                let row = document.querySelectorAll('#fields-area .row')[idx];
                                if(row) {
                                    let colPrimary = document.createElement('div');
                                    colPrimary.className = 'col-md-2';
                                    colPrimary.innerHTML = `<div class="form-check"><input type="checkbox" name="fields[${idx}][is_primary]" class="form-check-input" id="primary${idx}"><label class="form-check-label" for="primary${idx}">Llave primaria</label></div>`;
                                    let colForeign = document.createElement('div');
                                    colForeign.className = 'col-md-2';
                                    colForeign.innerHTML = `<div class="form-check"><input type="checkbox" name="fields[${idx}][is_foreign]" class="form-check-input foreign-check" data-index="${idx}" id="foreign${idx}"><label class="form-check-label" for="foreign${idx}">Llave foránea</label></div>`;
                                    let colForeignFields = document.createElement('div');
                                    colForeignFields.className = 'col-md-3 foreign-fields';
                                    colForeignFields.id = `foreign-fields-${idx}`;
                                    colForeignFields.style.display = 'none';
                                    colForeignFields.innerHTML = `<input name="fields[${idx}][foreign_table]" class="form-control mb-1" placeholder="Tabla destino" /><input name="fields[${idx}][foreign_column]" class="form-control" placeholder="Columna destino" /><select name="fields[${idx}][on_delete]" class="form-select mt-1"><option value="cascade">Cascade</option><option value="restrict">Restrict</option><option value="set null">Set Null</option></select>`;
                                    row.appendChild(colPrimary);
                                    row.appendChild(colForeign);
                                    row.appendChild(colForeignFields);
                                    let foreignCheck = colForeign.querySelector('.foreign-check');
                                    foreignCheck.addEventListener('change', function(){
                                        if(this.checked){
                                            colForeignFields.style.display = 'block';
                                        } else {
                                            colForeignFields.style.display = 'none';
                                        }
                                    });
                                }
                                fieldIndex++;
                            }, 100);
                        });
                        // Validación en tiempo real para los nombres de campo
                        document.addEventListener('input', function(e) {
                            if (e.target.classList.contains('field-name')) {
                                const regex = /^[a-zA-Z0-9_]+$/;
                                const feedback = e.target.parentElement.querySelector('.invalid-feedback');
                                if (!regex.test(e.target.value)) {
                                    e.target.classList.add('is-invalid');
                                    feedback.classList.remove('d-none');
                                } else {
                                    e.target.classList.remove('is-invalid');
                                    feedback.classList.add('d-none');
                                }
                            }
                        });
                        // Validación al enviar el formulario
                        document.getElementById('table-setup-form').addEventListener('submit', function(e) {
                            let valid = true;
                            document.querySelectorAll('.field-name').forEach(function(input) {
                                const regex = /^[a-zA-Z0-9_]+$/;
                                const feedback = input.parentElement.querySelector('.invalid-feedback');
                                if (!regex.test(input.value)) {
                                    input.classList.add('is-invalid');
                                    feedback.classList.remove('d-none');
                                    valid = false;
                                } else {
                                    input.classList.remove('is-invalid');
                                    feedback.classList.add('d-none');
                                }
                            });
                            if (!valid) {
                                e.preventDefault();
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
