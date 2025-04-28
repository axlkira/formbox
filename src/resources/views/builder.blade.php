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
                <div id="grid-controls"></div>
                <div class="card-body" id="form-design"></div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
let fieldCount = 0;
let fields = [];
let gridRows = 3;
let gridCols = 3;

function sanitizeName(label) {
    return label
        .toLowerCase()
        .replace(/[^a-z0-9]/gi, '_')
        .replace(/_+/g, '_')
        .replace(/^_+|_+$/g, '')
        .substring(0, 20);
}

function renderGridDesign() {
    renderGridControls();
    let html = '<div class="container-fluid"><div class="row">';
    for (let r = 0; r < gridRows; r++) {
        html += '<div class="d-flex mb-2">';
        for (let c = 0; c < gridCols; c++) {
            const cellIndex = r * gridCols + c;
            const field = fields.find(f => f.gridPos && f.gridPos.row === r && f.gridPos.col === c);
            html += `<div class='grid-cell border rounded me-2 p-2 flex-fill bg-light' style='min-height:70px;' data-row='${r}' data-col='${c}'>`;
            if (field) {
                html += `<div class='field-item border rounded p-2 bg-white d-flex justify-content-between align-items-center' tabindex='0' data-id='${field.id}' style='cursor:pointer;'>
                    <span><span class='badge bg-secondary me-2'>${field.type}</span> <span class='field-label'>${field.label}</span></span>
                    <button type='button' class='btn btn-sm btn-danger btn-remove-field' data-id='${field.id}' title='Eliminar campo'><i class='bi bi-x-lg'></i></button>
                </div>`;
            }
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div></div>';
    $('#form-design').html(html);
}

function renderGridControls() {
    let html = `<div class='mb-2 d-flex justify-content-end gap-2'>
        <button type='button' class='btn btn-outline-primary btn-sm' id='add-row'><i class='bi bi-plus-square'></i> Fila</button>
        <button type='button' class='btn btn-outline-danger btn-sm' id='remove-row'><i class='bi bi-dash-square'></i> Fila</button>
        <button type='button' class='btn btn-outline-primary btn-sm' id='add-col'><i class='bi bi-plus-square'></i> Columna</button>
        <button type='button' class='btn btn-outline-danger btn-sm' id='remove-col'><i class='bi bi-dash-square'></i> Columna</button>
        <button type='button' class='btn btn-outline-secondary btn-sm' id='duplicate-row'><i class='bi bi-files'></i> Duplicar fila</button>
    </div>`;
    $('#grid-controls').html(html);
}

function renderPreview() {
    let html = '<form><div class="container-fluid"><div class="row">';
    for (let r = 0; r < gridRows; r++) {
        html += '<div class="d-flex mb-2">';
        for (let c = 0; c < gridCols; c++) {
            const field = fields.find(f => f.gridPos && f.gridPos.row === r && f.gridPos.col === c);
            html += '<div class="flex-fill me-2">';
            if (field) {
                let extra = '';
                if (field.hidden) extra += ' hidden';
                if (field.disabled) extra += ' disabled';
                if (field.required) extra += ' required';
                html += `<div class='mb-3'${field.hidden ? ' style="display:none;"' : ''}>`;
                if (field.type !== 'button') {
                    html += `<label class='form-label' for='${field.id_html}'>${field.label}</label>`;
                }
                if (field.type === 'input') {
                    html += `<input type='text' class='form-control' id='${field.id_html}' name='${field.name_html}' placeholder='${field.placeholder || field.label}' value='${field.defaultValue || ''}'${extra}>`;
                } else if (field.type === 'textarea') {
                    html += `<textarea class='form-control' id='${field.id_html}' name='${field.name_html}' placeholder='${field.placeholder || field.label}'${extra}>${field.defaultValue || ''}</textarea>`;
                } else if (field.type === 'select') {
                    html += `<select class='form-select' id='${field.id_html}' name='${field.name_html}'${extra}>`;
                    if (Array.isArray(field.options) && field.options.length > 0) {
                        field.options.forEach(opt => {
                            html += `<option${field.defaultValue === opt ? ' selected' : ''}>${opt}</option>`;
                        });
                    } else {
                        html += `<option>Opción 1</option>`;
                    }
                    html += `</select>`;
                } else if (field.type === 'checkbox') {
                    html += `<div class='form-check'><input type='checkbox' class='form-check-input' id='${field.id_html}' name='${field.name_html}'${extra}${field.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${field.id_html}'>${field.label}</label></div>`;
                } else if (field.type === 'switch') {
                    html += `<div class='form-check form-switch'><input class='form-check-input' type='checkbox' id='${field.id_html}' name='${field.name_html}'${extra}${field.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${field.id_html}'>${field.label}</label></div>`;
                } else if (field.type === 'button') {
                    html += `<button type='button' class='btn btn-primary'${extra}>${field.label}</button>`;
                }
                html += '</div>';
            }
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div></div></form>';
    $('#preview-area').html(html);
}

$(function() {
    if ($('#grid-controls').length === 0) {
        $('#form-design').before("<div id='grid-controls'></div>");
    }
    renderGridDesign();
    $(".draggable").draggable({
        helper: "clone",
        revert: "invalid"
    });
    $(document).on('mouseenter', '.grid-cell', function() {
        $(this).droppable({
            accept: ".draggable, .field-item",
            drop: function(event, ui) {
                const row = $(this).data('row');
                const col = $(this).data('col');
                let type, label, id, field;
                if (ui.draggable.hasClass('draggable')) {
                    // Nuevo campo
                    fieldCount++;
                    type = ui.draggable.data('type');
                    label = `Etiqueta ${fieldCount}`;
                    const name_html = sanitizeName(label);
                    const id_html = name_html;
                    field = { id: fieldCount, type, label, name_html, id_html, gridPos: {row, col} };
                    if (type === 'select') field.options = ['Opción 1'];
                    fields.push(field);
                } else if (ui.draggable.hasClass('field-item')) {
                    // Mover campo existente
                    id = ui.draggable.data('id');
                    field = fields.find(f => f.id === id);
                    if (field) field.gridPos = {row, col};
                }
                renderGridDesign();
                renderPreview();
            }
        });
    });
    $(document).on('click', '.btn-remove-field', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        fields = fields.filter(f => f.id !== id);
        renderGridDesign();
        renderPreview();
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
            renderGridDesign();
            renderPreview();
        }
    });
    $(document).off('click', '#add-row').on('click', '#add-row', function() {
        gridRows++;
        renderGridDesign();
        renderPreview();
    });
    $(document).off('click', '#remove-row').on('click', '#remove-row', function() {
        if (gridRows > 1) {
            // Eliminar campos que estén en la última fila
            fields = fields.filter(f => !(f.gridPos && f.gridPos.row === gridRows - 1));
            gridRows--;
            renderGridDesign();
            renderPreview();
        }
    });
    $(document).off('click', '#add-col').on('click', '#add-col', function() {
        gridCols++;
        renderGridDesign();
        renderPreview();
    });
    $(document).off('click', '#remove-col').on('click', '#remove-col', function() {
        if (gridCols > 1) {
            // Eliminar campos que estén en la última columna
            fields = fields.filter(f => !(f.gridPos && f.gridPos.col === gridCols - 1));
            gridCols--;
            renderGridDesign();
            renderPreview();
        }
    });
    $(document).off('click', '#duplicate-row').on('click', '#duplicate-row', function() {
        Swal.fire({
            title: 'Duplicar fila',
            input: 'number',
            inputLabel: '¿Qué número de fila deseas duplicar? (1 a ' + gridRows + ')',
            inputAttributes: {
                min: 1,
                max: gridRows,
                step: 1
            },
            inputValidator: (value) => {
                if (!value || isNaN(value) || value < 1 || value > gridRows) {
                    return 'Debes ingresar un número válido de fila';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Duplicar',
            cancelButtonText: 'Cancelar',
            preConfirm: (value) => {
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let row = parseInt(result.value) - 1;
                // Insertar una nueva fila debajo
                gridRows++;
                // 1. Mover hacia abajo los campos de filas inferiores
                for (let r = gridRows - 2; r > row; r--) {
                    fields.forEach(f => {
                        if (f.gridPos && f.gridPos.row === r) {
                            f.gridPos.row = r + 1;
                        }
                    });
                }
                // 2. Duplicar los campos de la fila elegida
                let newFields = [];
                for (let c = 0; c < gridCols; c++) {
                    const origField = fields.find(f => f.gridPos && f.gridPos.row === row && f.gridPos.col === c);
                    if (origField) {
                        let newField = JSON.parse(JSON.stringify(origField));
                        newField.id = ++fieldCount;
                        newField.gridPos.row = row + 1;
                        newField.label = newField.label + ' (copia)';
                        newField.name_html = newField.name_html + '_copy' + fieldCount;
                        newField.id_html = newField.id_html + '_copy' + fieldCount;
                        newFields.push(newField);
                    }
                }
                fields = fields.concat(newFields);
                renderGridDesign();
                renderPreview();
                Swal.fire('¡Fila duplicada!', '', 'success');
            }
        });
    });
});
</script>
@endpush
