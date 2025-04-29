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
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="fields[{{$i}}][required]" class="form-check-input" id="required{{$i}}" @if(isset($field['required'])) checked @endif>
                                                <label class="form-check-label" for="required{{$i}}">Requerido</label>
                                            </div>
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
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="fields[0][required]" class="form-check-input" id="required0">
                                            <label class="form-check-label" for="required0">Requerido</label>
                                        </div>
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
                        document.getElementById('add-field').addEventListener('click', function() {
                            const area = document.getElementById('fields-area');
                            const div = document.createElement('div');
                            div.className = 'row g-2 mb-2 align-items-center';
                            div.innerHTML = `
                                <div class=\"col-md-5\">
                                    <input name=\"fields[${fieldIndex}][name]\" class=\"form-control bg-light border-2 border-primary-subtle rounded-3 shadow-sm field-name\" placeholder=\"Nombre (sin espacios)\" pattern=\"^[a-zA-Z0-9_]+$\" title=\"Solo letras, números y guion bajo\" required />
                                    <div class=\"invalid-feedback d-none\">Solo letras, números y guion bajo (sin espacios)</div>
                                </div>
                                <div class=\"col-md-4\">
                                    <select name=\"fields[${fieldIndex}][type]\" class=\"form-select bg-light border-2 border-primary-subtle rounded-3 shadow-sm\">
                                        <option value=\"string\">Texto</option>
                                        <option value=\"integer\">Número</option>
                                        <option value=\"boolean\">Switch</option>
                                        <option value=\"text\">Textarea</option>
                                        <option value=\"date\">Fecha</option>
                                    </select>
                                </div>
                                <div class=\"col-md-3\">
                                    <div class=\"form-check\">
                                        <input type=\"checkbox\" name=\"fields[${fieldIndex}][required]\" class=\"form-check-input\" id=\"required${fieldIndex}\">
                                        <label class=\"form-check-label\" for=\"required${fieldIndex}\">Requerido</label>
                                    </div>
                                </div>
                            `;
                            area.appendChild(div);
                            fieldIndex++;
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
