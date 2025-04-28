@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Builder Drag & Drop</h2>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Campos disponibles</div>
                <div class="card-body" id="toolbox">
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="input"><i class="bi bi-input-cursor"></i> Input texto</div>
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="textarea"><i class="bi bi-card-text"></i> Textarea</div>
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="select"><i class="bi bi-list"></i> Select</div>
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="checkbox"><i class="bi bi-check-square"></i> Checkbox</div>
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="switch"><i class="bi bi-toggle-on"></i> Switch</div>
                    <div class="draggable btn btn-outline-primary w-100 mb-2" data-type="button"><i class="bi bi-box-arrow-in-right"></i> Botón</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Diseño del formulario</div>
                <div class="card-body droppable min-vh-50" id="form-design" style="min-height:350px; background: #f8f9fa;">
                    <p class="text-muted text-center" id="placeholder-design">Arrastra aquí los campos para construir tu formulario.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Propiedades del campo</div>
                <div class="card-body" id="field-properties">
                    <p class="text-muted">Selecciona un campo para editar sus propiedades.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Vista previa en tiempo real</div>
                <div class="card-body" id="preview-area">
                    <p class="text-muted">Aquí verás una vista previa de tu formulario.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
let fieldCount = 0;
let fields = [];

function sanitizeName(label) {
    return label
        .toLowerCase()
        .replace(/[^a-z0-9]/gi, '_')
        .replace(/_+/g, '_')
        .replace(/^_+|_+$/g, '')
        .substring(0, 20);
}

function renderPreview() {
    let html = '<form>';
    fields.forEach(f => {
        let extra = '';
        if (f.hidden) extra += ' hidden';
        if (f.disabled) extra += ' disabled';
        if (f.required) extra += ' required';
        html += '<div class="mb-3"' + (f.hidden ? ' style="display:none;"' : '') + '>';
        if (f.type !== 'button') {
            html += `<label class='form-label' for='${f.id_html}'>${f.label}</label>`;
        }
        if (f.type === 'input') {
            html += `<input type='text' class='form-control' id='${f.id_html}' name='${f.name_html}' placeholder='${f.placeholder || f.label}' value='${f.defaultValue || ''}'${extra}>`;
        } else if (f.type === 'textarea') {
            html += `<textarea class='form-control' id='${f.id_html}' name='${f.name_html}' placeholder='${f.placeholder || f.label}'${extra}>${f.defaultValue || ''}</textarea>`;
        } else if (f.type === 'select') {
            html += `<select class='form-select' id='${f.id_html}' name='${f.name_html}'${extra}>`;
            if (Array.isArray(f.options) && f.options.length > 0) {
                f.options.forEach(opt => {
                    html += `<option${f.defaultValue === opt ? ' selected' : ''}>${opt}</option>`;
                });
            } else {
                html += `<option>Opción 1</option>`;
            }
            html += `</select>`;
        } else if (f.type === 'checkbox') {
            html += `<div class='form-check'><input type='checkbox' class='form-check-input' id='${f.id_html}' name='${f.name_html}'${extra}${f.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${f.id_html}'>${f.label}</label></div>`;
        } else if (f.type === 'switch') {
            html += `<div class='form-check form-switch'><input class='form-check-input' type='checkbox' id='${f.id_html}' name='${f.name_html}'${extra}${f.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${f.id_html}'>${f.label}</label></div>`;
        } else if (f.type === 'button') {
            html += `<button type='button' class='btn btn-primary'${extra}>${f.label}</button>`;
        }
        html += '</div>';
    });
    html += '</form>';
    $('#preview-area').html(html);
}

$(function() {
    $(".draggable").draggable({
        helper: "clone",
        revert: "invalid"
    });
    $("#form-design").droppable({
        accept: ".draggable",
        drop: function(event, ui) {
            $("#placeholder-design").hide();
            const type = ui.draggable.data('type');
            fieldCount++;
            const label = `Etiqueta ${fieldCount}`;
            const name_html = sanitizeName(label);
            const id_html = name_html;
            const field = { id: fieldCount, type: type, label: label, name_html: name_html, id_html: id_html };
            if (type === 'select') {
                field.options = ['Opción 1'];
            }
            fields.push(field);
            const fieldHtml = `<div class='field-item border rounded p-2 mb-2 bg-white d-flex justify-content-between align-items-center' tabindex='0' data-id='${field.id}' style='cursor:pointer;'>
                <span><span class='badge bg-secondary me-2'>${type}</span> <span class='field-label'>${field.label}</span></span>
                <button type='button' class='btn btn-sm btn-danger btn-remove-field' data-id='${field.id}' title='Eliminar campo'><i class='bi bi-x-lg'></i></button>
            </div>`;
            $('#form-design').append(fieldHtml);
            renderPreview();
        }
    });
    $(document).on('click', '.btn-remove-field', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        fields = fields.filter(f => f.id !== id);
        $(`.field-item[data-id='${id}']`).remove();
        renderPreview();
        if (fields.length === 0) {
            $('#placeholder-design').show();
        }
        $('#field-properties').html('<p class="text-muted">Selecciona un campo para editar sus propiedades.</p>');
    });
    $(document).on('click', '.field-item', function() {
        const id = $(this).data('id');
        const field = fields.find(f => f.id === id);
        if (field) {
            let html = `<div class='mb-3'>
                <label>Etiqueta</label>
                <input type='text' class='form-control' id='edit-label' value='${field.label}'>
            </div>`;
            html += `<div class='mb-3'>
                <label>name</label>
                <input type='text' class='form-control' id='edit-name' value='${field.name_html || ''}'>
            </div>`;
            html += `<div class='mb-3'>
                <label>id</label>
                <input type='text' class='form-control' id='edit-id' value='${field.id_html || ''}'>
            </div>`;
            html += `<div class='mb-3'>
                <label>Placeholder</label>
                <input type='text' class='form-control' id='edit-placeholder' value='${field.placeholder || ''}'>
            </div>`;
            html += `<div class='mb-3'>
                <label>Valor por defecto</label>
                <input type='text' class='form-control' id='edit-default' value='${field.defaultValue || ''}'>
            </div>`;
            html += `<div class='form-check form-switch mb-2'>
                <input class='form-check-input' type='checkbox' id='edit-hidden' ${field.hidden ? 'checked' : ''}>
                <label class='form-check-label' for='edit-hidden'>Oculto (hidden)</label>
            </div>`;
            html += `<div class='form-check form-switch mb-2'>
                <input class='form-check-input' type='checkbox' id='edit-disabled' ${field.disabled ? 'checked' : ''}>
                <label class='form-check-label' for='edit-disabled'>Deshabilitado (disabled)</label>
            </div>`;
            html += `<div class='form-check form-switch mb-3'>
                <input class='form-check-input' type='checkbox' id='edit-required' ${field.required ? 'checked' : ''}>
                <label class='form-check-label' for='edit-required'>Requerido (required)</label>
            </div>`;
            if (field.type === 'select') {
                html += `<div class='mb-3'>
                    <label>Opciones (separadas por coma)</label>
                    <input type='text' class='form-control' id='edit-options' value='${field.options ? field.options.join(", ") : ''}'>
                </div>`;
            }
            html += `<button class='btn btn-success w-100' id='save-props'>Guardar</button>`;
            $('#field-properties').html(html).data('field-id', id);
        }
        $('.field-item').removeClass('border-primary');
        $(this).addClass('border-primary');
    });
    $(document).on('click', '#save-props', function() {
        const id = $('#field-properties').data('field-id');
        const newLabel = $('#edit-label').val();
        let newName = $('#edit-name').val();
        let newId = $('#edit-id').val();
        const placeholder = $('#edit-placeholder').val();
        const defaultValue = $('#edit-default').val();
        const hidden = $('#edit-hidden').is(':checked');
        const disabled = $('#edit-disabled').is(':checked');
        const required = $('#edit-required').is(':checked');
        const field = fields.find(f => f.id === id);
        if (field) {
            field.label = newLabel;
            if (!newName) newName = sanitizeName(newLabel);
            if (!newId) newId = sanitizeName(newLabel);
            field.name_html = newName.substring(0, 20);
            field.id_html = newId.substring(0, 20);
            field.placeholder = placeholder;
            field.defaultValue = defaultValue;
            field.hidden = hidden;
            field.disabled = disabled;
            field.required = required;
            $(`.field-item[data-id='${id}'] .field-label`).text(newLabel);
            if (field.type === 'select') {
                const opts = $('#edit-options').val().split(',').map(s => s.trim()).filter(Boolean);
                field.options = opts.length ? opts : ['Opción 1'];
            }
            renderPreview();
        }
    });
});
</script>
@endpush
