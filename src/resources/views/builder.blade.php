@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Builder Drag & Drop</h2>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Campos disponibles</div>
                <div class="card-body" id="toolbox">
                    <div class="draggable btn btn-outline-primary" data-type="input"><i class="bi bi-input-cursor"></i> Input texto</div>
                    <div class="draggable btn btn-outline-primary" data-type="textarea"><i class="bi bi-card-text"></i> Textarea</div>
                    <div class="draggable btn btn-outline-primary" data-type="select"><i class="bi bi-list"></i> Select</div>
                    <div class="draggable btn btn-outline-primary" data-type="checkbox"><i class="bi bi-check-square"></i> Checkbox</div>
                    <div class="draggable btn btn-outline-primary" data-type="switch"><i class="bi bi-toggle-on"></i> Switch</div>
                    <div class="draggable btn btn-outline-primary" data-type="button"><i class="bi bi-box-arrow-in-right"></i> Botón</div>
                    <div class="draggable btn btn-outline-primary draggable-radio" data-type="radio"><i class="bi bi-record-circle me-1"></i> Radio</div>
                    <div class="draggable btn btn-outline-primary draggable-card" data-type="card"><i class="bi bi-card-text me-1"></i> Card</div>
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

function initPanelDraggables() {
    $("#toolbox .draggable").draggable({
        helper: function() {
            // Helper compacto y bonito
            return $(this).clone()
                .css({
                    width: '120px',
                    'min-width': 'unset',
                    'max-width': 'unset',
                    'padding': '6px 12px',
                    'font-size': '15px',
                    'background': '#fff',
                    'border': '1.5px solid #007bff',
                    'border-radius': '8px',
                    'box-shadow': '0 2px 8px rgba(0,0,0,0.12)',
                    'z-index': 99999,
                    'opacity': 0.95
                })
                .removeClass('w-100') // Quita el ancho completo del botón original
                .removeClass('mb-2');
        },
        revert: "invalid",
        zIndex: 9999,
        appendTo: 'body',
        start: function() { $(this).addClass('dragging'); },
        stop: function() { $(this).removeClass('dragging'); }
    });
}

function renderGridDesign() {
    renderGridControls();
    let html = '<div class="container-fluid"><div class="row">';
    for (let r = 0; r < gridRows; r++) {
        html += '<div class="d-flex mb-2">';
        for (let c = 0; c < gridCols; c++) {
            html += `<div class='grid-cell border rounded me-2 p-2 flex-fill bg-light position-relative' style='min-height:70px;' data-row='${r}' data-col='${c}'>`;
            const field = fields.find(f => f.gridPos && f.gridPos.row === r && f.gridPos.col === c);
            if (field) {
                if (field.type === 'card') {
                    html += `<div class='field-item card-field' data-id='${field.id}' draggable='true'>
                        <span class='badge bg-secondary me-1'>card</span> <span class='field-label'>${field.label}</span>
                        <button class='btn btn-xs btn-link text-danger position-absolute top-0 end-0 remove-field-cell' data-id='${field.id}' title='Eliminar campo'><i class='bi bi-x-circle'></i></button>
                        <div class='card-droppable' data-card-id='${field.id}' style='min-height:40px; border:1px dashed #bbb; margin-top:5px; padding:3px;'>`;
                    (field.children || []).forEach(childId => {
                        const child = fields.find(f => f.id === childId);
                        if (child) {
                            html += `<div class='field-item' data-id='${child.id}' draggable='true'>${child.label}
                                <button class='btn btn-xs btn-link text-danger float-end remove-field-cell' data-id='${child.id}' title='Eliminar campo'><i class='bi bi-x-circle'></i></button>
                            </div>`;
                        }
                    });
                    html += `</div></div>`;
                } else {
                    html += `<div class='field-item' data-id='${field.id}' draggable='true'>${field.label}
                        <button class='btn btn-xs btn-link text-danger position-absolute top-0 end-0 remove-field-cell' data-id='${field.id}' title='Eliminar campo'><i class='bi bi-x-circle'></i></button>
                    </div>`;
                }
            }
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div></div>';
    $('#form-design').html(html);

    // Hacer campos existentes draggables
    $('.field-item').draggable({
        helper: 'clone',
        revert: 'invalid',
        zIndex: 9999,
        appendTo: 'body',
        start: function() { $(this).addClass('dragging'); },
        stop: function() { $(this).removeClass('dragging'); }
    });
    // Hacer celdas droppables
    $('.grid-cell').droppable({
        accept: '.field-item, .draggable',
        drop: function(event, ui) {
            const row = $(this).data('row');
            const col = $(this).data('col');
            let field;
            if (ui.draggable.hasClass('draggable')) {
                // Crear nuevo campo desde el panel izquierdo
                fieldCount++;
                const type = ui.draggable.data('type');
                const label = `Etiqueta ${fieldCount}`;
                const name_html = sanitizeName(label);
                const id_html = name_html;
                field = { id: fieldCount, type, label, name_html, id_html, gridPos: {row, col} };
                if (type === 'select') field.options = ['Opción 1'];
                if (type === 'radio') field.options = ['Opción 1', 'Opción 2'];
                if (type === 'card') { field.cardTitle = 'Título de la card'; field.cardContent = ''; field.children = []; }
                fields.push(field);
            } else if (ui.draggable.hasClass('field-item')) {
                // Mover campo existente
                const id = ui.draggable.data('id');
                field = fields.find(f => f.id === id);
                if (field) {
                    // Si estaba dentro de una card, eliminarlo de los hijos
                    fields.forEach(f => {
                        if (f.type === 'card' && f.children) {
                            f.children = f.children.filter(cid => cid !== id);
                        }
                    });
                    field.gridPos = {row, col};
                    delete field.parentCard;
                }
            }
            renderGridDesign();
            renderPreview();
        }
    });
    // Hacer cards droppables para hijos
    $('.card-droppable').droppable({
        accept: '.field-item, .draggable',
        drop: function(event, ui) {
            const cardId = $(this).data('card-id');
            const card = fields.find(f => f.id === cardId);
            let field;
            if (ui.draggable.hasClass('draggable')) {
                // Crear nuevo campo dentro de la card
                fieldCount++;
                const type = ui.draggable.data('type');
                const label = `Etiqueta ${fieldCount}`;
                const name_html = sanitizeName(label);
                const id_html = name_html;
                field = { id: fieldCount, type, label, name_html, id_html, parentCard: cardId };
                if (type === 'select') field.options = ['Opción 1'];
                if (type === 'radio') field.options = ['Opción 1', 'Opción 2'];
                if (type === 'card') { field.cardTitle = 'Título de la card'; field.cardContent = ''; field.children = []; }
                fields.push(field);
            } else if (ui.draggable.hasClass('field-item')) {
                // Mover campo existente dentro de la card
                const id = ui.draggable.data('id');
                field = fields.find(f => f.id === id);
                if (field) {
                    delete field.gridPos;
                    // Quitar de otro card
                    fields.forEach(f => {
                        if (f.type === 'card' && f.children) {
                            f.children = f.children.filter(cid => cid !== id);
                        }
                    });
                    field.parentCard = cardId;
                }
            }
            // Agregar como hijo
            if (card && field && card.id !== field.id) {
                card.children = card.children || [];
                if (!card.children.includes(field.id)) {
                    card.children.push(field.id);
                }
            }
            renderGridDesign();
            renderPreview();
        }
    });
    // REINICIALIZAR los draggables del panel izquierdo
    initPanelDraggables();
}

function renderGridControls() {
    let html = `<div class='mb-2 d-flex justify-content-end gap-2'>
        <button type='button' class='btn btn-outline-primary btn-sm' id='add-row'><i class='bi bi-plus-square'></i> Fila</button>
        <button type='button' class='btn btn-outline-danger btn-sm' id='remove-row'><i class='bi bi-dash-square'></i> Fila</button>
        <button type='button' class='btn btn-outline-primary btn-sm' id='add-col'><i class='bi bi-plus-square'></i> Columna</button>
        <button type='button' class='btn btn-outline-secondary btn-sm' id='duplicate-row'><i class='bi bi-files'></i> Duplicar fila</button>
    </div>`;
    // Cabecera de columnas con botón de eliminar
    html += `<div class='d-flex mb-1'>`;
    for (let c = 0; c < gridCols; c++) {
        html += `<div class='flex-fill text-center position-relative' style='min-width:70px;'>
            <span class='text-secondary'>Col ${c+1}</span>
            <button class='btn btn-xs btn-link text-danger position-absolute top-0 end-0 remove-col-manual' data-col='${c}' title='Eliminar columna'><i class='bi bi-x-circle'></i></button>
        </div>`;
    }
    html += `</div>`;
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
                if (field.type === 'card') {
                    html += `<div class='card'><div class='card-header'>${field.cardTitle || ''}</div><div class='card-body'>`;
                    // Renderizar hijos
                    (field.children || []).forEach(childId => {
                        const child = fields.find(f => f.id === childId);
                        if (child) {
                            html += renderFieldPreview(child);
                        }
                    });
                    html += `${field.cardContent || ''}</div></div>`;
                } else {
                    html += renderFieldPreview(field);
                }
            }
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div></div></form>';
    $('#preview-area').html(html);
}

function renderFieldPreview(field) {
    let extra = '';
    if (field.hidden) extra += ' hidden';
    if (field.disabled) extra += ' disabled';
    if (field.required) extra += ' required';
    let html = `<div class='mb-3'${field.hidden ? ' style="display:none;"' : ''}>`;
    if (field.type === 'radio') {
        html += `<label class='form-label'>${field.label}</label><div>`;
        (field.options || []).forEach(opt => {
            html += `<div class='form-check form-check-inline'><input class='form-check-input' type='radio' name='${field.name_html}' value='${opt}' id='${field.id_html}_${opt}'${field.defaultValue === opt ? ' checked' : ''}${extra}><label class='form-check-label' for='${field.id_html}_${opt}'>${opt}</label></div>`;
        });
        html += `</div>`;
    } else if (field.type === 'card') {
        // Las cards se renderizan en el ciclo principal
    } else if (field.type === 'input') {
        html += `<label class='form-label' for='${field.id_html}'>${field.label}</label><input type='text' class='form-control' id='${field.id_html}' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'checkbox') {
        html += `<div class='form-check'><input type='checkbox' class='form-check-input' id='${field.id_html}' name='${field.name_html}'${extra}${field.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${field.id_html}'>${field.label}</label></div>`;
    } else if (field.type === 'switch') {
        html += `<div class='form-check form-switch'><input class='form-check-input' type='checkbox' id='${field.id_html}' name='${field.name_html}'${extra}${field.defaultValue === 'on' ? ' checked' : ''}><label class='form-check-label' for='${field.id_html}'>${field.label}</label></div>`;
    } else if (field.type === 'button') {
        html += `<button type='button' class='btn btn-primary'${extra}>${field.label}</button>`;
    } else if (field.type === 'select') {
        html += `<label class='form-label' for='${field.id_html}'>${field.label}</label><select class='form-select' id='${field.id_html}' name='${field.name_html}'${extra}>`;
        (field.options || []).forEach(opt => {
            html += `<option value='${opt}'>${opt}</option>`;
        });
        html += `</select>`;
    }
    html += '</div>';
    return html;
}

$(function() {
    if ($('#grid-controls').length === 0) {
        $('#form-design').before("<div id='grid-controls'></div>");
    }
    initPanelDraggables();
    renderGridDesign();
    $(document).on('mouseenter', '.grid-cell', function() {
        $(this).droppable({
            accept: ".draggable, .field-item",
            drop: function(event, ui) {
                const row = $(this).data('row');
                const col = $(this).data('col');
                let type, label, id, field;
                if (ui.draggable.hasClass('draggable')) {
                    fieldCount++;
                    type = ui.draggable.data('type');
                    label = `Etiqueta ${fieldCount}`;
                    const name_html = sanitizeName(label);
                    const id_html = name_html;
                    field = { id: fieldCount, type, label, name_html, id_html, gridPos: {row, col} };
                    if (type === 'select') field.options = ['Opción 1'];
                    if (type === 'radio') field.options = ['Opción 1', 'Opción 2'];
                    if (type === 'card') {
                        field.cardTitle = 'Título de la card';
                        field.cardContent = '';
                        field.children = [];
                    }
                    fields.push(field);
                } else if (ui.draggable.hasClass('field-item')) {
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
            if (field.type === 'radio') {
                html += `<div class='mb-3'>
                    <label>Opciones (separadas por coma)</label>
                    <input type='text' class='form-control' id='edit-options' value='${field.options ? field.options.join(", ") : ''}'>
                </div>`;
                html += `<div class='mb-3'>
                    <label>Valor por defecto</label>
                    <input type='text' class='form-control' id='edit-default' value='${field.defaultValue || ''}'>
                </div>`;
            }
            if (field.type === 'card') {
                html += `<div class='mb-3'>
                    <label>Título</label>
                    <input type='text' class='form-control' id='edit-card-title' value='${field.cardTitle || ''}'>
                </div>`;
                html += `<div class='mb-3'>
                    <label>Contenido</label>
                    <textarea class='form-control' id='edit-card-content'>${field.cardContent || ''}</textarea>
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
        const field = fields.find(f => f.id === id);
        if (field) {
            field.label = newLabel;
            if (!newName) newName = sanitizeName(newLabel);
            if (!newId) newId = sanitizeName(newLabel);
            field.name_html = newName.substring(0, 20);
            field.id_html = newId.substring(0, 20);
            field.placeholder = $('#edit-placeholder').val();
            field.defaultValue = $('#edit-default').val();
            field.hidden = $('#edit-hidden').is(':checked');
            field.disabled = $('#edit-disabled').is(':checked');
            field.required = $('#edit-required').is(':checked');
            if (field.type === 'select') {
                const opts = $('#edit-options').val().split(',').map(s => s.trim()).filter(Boolean);
                field.options = opts.length ? opts : ['Opción 1'];
            }
            if (field.type === 'radio') {
                const opts = $('#edit-options').val().split(',').map(s => s.trim()).filter(Boolean);
                field.options = opts.length ? opts : ['Opción 1', 'Opción 2'];
                field.defaultValue = $('#edit-default').val();
            }
            if (field.type === 'card') {
                field.cardTitle = $('#edit-card-title').val();
                field.cardContent = $('#edit-card-content').val();
            }
            $(`.field-item[data-id='${id}'] .field-label`).text(newLabel);
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
                gridRows++;
                for (let r = gridRows - 2; r > row; r--) {
                    fields.forEach(f => {
                        if (f.gridPos && f.gridPos.row === r) {
                            f.gridPos.row = r + 1;
                        }
                    });
                }
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
    $(document).on('click', '.remove-col-manual', function() {
        const col = parseInt($(this).data('col'));
        // Eliminar campos de esa columna
        fields = fields.filter(f => !(f.gridPos && f.gridPos.col === col));
        // Reajustar posiciones de campos a la derecha
        fields.forEach(f => {
            if (f.gridPos && f.gridPos.col > col) {
                f.gridPos.col--;
            }
        });
        gridCols--;
        renderGridDesign();
        renderPreview();
    });
    $(document).on('click', '.remove-field-cell', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        // Eliminar campo del array fields
        fields = fields.filter(f => f.id !== id);
        // Si el campo era hijo de una card, quitarlo de su parent
        fields.forEach(f => {
            if (f.type === 'card' && f.children) {
                f.children = f.children.filter(cid => cid !== id);
            }
        });
        renderGridDesign();
        renderPreview();
    });
});
</script>
@endpush
