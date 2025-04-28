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
                    <div class="draggable btn btn-outline-primary" data-type="email"><i class="bi bi-envelope"></i> Email</div>
                    <div class="draggable btn btn-outline-primary" data-type="password"><i class="bi bi-lock"></i> Password</div>
                    <div class="draggable btn btn-outline-primary" data-type="number"><i class="bi bi-123"></i> Número</div>
                    <div class="draggable btn btn-outline-primary" data-type="date"><i class="bi bi-calendar"></i> Fecha</div>
                    <div class="draggable btn btn-outline-primary" data-type="time"><i class="bi bi-clock"></i> Hora</div>
                    <div class="draggable btn btn-outline-primary" data-type="file"><i class="bi bi-paperclip"></i> Archivo</div>
                    <div class="draggable btn btn-outline-primary" data-type="color"><i class="bi bi-palette"></i> Color</div>
                    <div class="draggable btn btn-outline-primary" data-type="range"><i class="bi bi-sliders"></i> Rango</div>
                    <div class="draggable btn btn-outline-primary" data-type="url"><i class="bi bi-link-45deg"></i> URL</div>
                    <div class="draggable btn btn-outline-primary" data-type="tel"><i class="bi bi-telephone"></i> Teléfono</div>
                    <div class="draggable btn btn-outline-primary" data-type="select_multiple"><i class="bi bi-list-check"></i> Select múltiple</div>
                    <div class="draggable btn btn-outline-primary" data-type="static"><i class="bi bi-info-circle"></i> Texto/HTML</div>
                    <div class="draggable btn btn-outline-primary" data-type="textarea"><i class="bi bi-card-text"></i> Textarea</div>
                    <div class="draggable btn btn-outline-primary" data-type="select"><i class="bi bi-list"></i> Select</div>
                    <div class="draggable btn btn-outline-primary" data-type="checkbox"><i class="bi bi-check-square"></i> Checkbox</div>
                    <div class="draggable btn btn-outline-primary" data-type="switch"><i class="bi bi-toggle-on"></i> Switch</div>
                    <div class="draggable btn btn-outline-primary" data-type="button"><i class="bi bi-box-arrow-in-right"></i> Botón</div>
                    <div class="draggable btn btn-outline-primary" data-type="radio"><i class="bi bi-record-circle me-1"></i> Radio</div>
                    <div class="draggable btn btn-outline-primary" data-type="card"><i class="bi bi-card-text me-1"></i> Card</div>
                    <div class="draggable btn btn-outline-primary" data-type="table"><i class="bi bi-table"></i> Tabla</div>
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
                <div class="card-body" id="properties">
                    <div id="properties-panel"></div>
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
let selectedFieldId = null;

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
            html += `<div class='grid-cell flex-fill border rounded me-2 p-2' style='min-height:50px;' data-row='${r}' data-col='${c}'>`;
            const field = fields.find(f => f.gridPos && f.gridPos.row === r && f.gridPos.col === c);
            if (field && !field.hidden) {
                html += renderFieldSelectable(field);
            }
            html += '</div>';
        }
        html += '</div>';
    }
    html += '</div></div>';
    $('#form-design').html(html);
    $(document).on('mouseenter', '.field-item', function() {
        if (!$(this).hasClass('ui-draggable')) {
            $(this).draggable({
                helper: 'clone',
                revert: 'invalid',
                zIndex: 1000,
                start: function(e, ui) {
                    $(ui.helper).addClass('dragging-field');
                }
            });
        }
    });
    $(document).on('mouseenter', '.grid-cell', function() {
        if (!$(this).hasClass('ui-droppable')) {
            $(this).droppable({
                accept: '.field-item',
                hoverClass: 'drop-hover',
                drop: function(event, ui) {
                    const fieldId = $(ui.draggable).data('id');
                    const row = $(this).data('row');
                    const col = $(this).data('col');
                    // Actualiza gridPos del campo
                    const field = fields.find(f => f.id === fieldId);
                    if (field) {
                        field.gridPos = { row: row, col: col };
                        renderGridDesign();
                        renderPreview();
                        renderPropertiesPanel();
                    }
                }
            });
        }
    });
    $('.field-item').draggable({
        helper: 'clone',
        revert: 'invalid',
        zIndex: 9999,
        appendTo: 'body',
        start: function() { $(this).addClass('dragging'); },
        stop: function() { $(this).removeClass('dragging'); }
    });
    $('.grid-cell').droppable({
        accept: '.field-item, .draggable',
        drop: function(event, ui) {
            const row = $(this).data('row');
            const col = $(this).data('col');
            let field;
            if (ui.draggable.hasClass('draggable')) {
                fieldCount++;
                const type = ui.draggable.data('type');
                const label = `Etiqueta ${fieldCount}`;
                const name_html = sanitizeName(label);
                const id_html = name_html;
                field = { id: fieldCount, type, label, name_html, id_html, gridPos: {row, col} };
                if (type === 'select') field.options = ['Opción 1'];
                if (type === 'radio') field.options = ['Opción 1', 'Opción 2'];
                if (type === 'card') { field.cardTitle = 'Título de la card'; field.cardContent = ''; field.children = []; }
                if (type === 'table') { field.columns = [{ colId: generateColId(), name: 'Columna 1', type: 'text', options: [] }, { colId: generateColId(), name: 'Columna 2', type: 'text', options: [] }]; field.rows = 3; }
                fields.push(field);
            } else if (ui.draggable.hasClass('field-item')) {
                const id = ui.draggable.data('id');
                field = fields.find(f => f.id === id);
                if (field) {
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
    $('.card-droppable').droppable({
        accept: '.field-item, .draggable',
        drop: function(event, ui) {
            const cardId = $(this).data('card-id');
            const card = fields.find(f => f.id === cardId);
            let field;
            if (ui.draggable.hasClass('draggable')) {
                fieldCount++;
                const type = ui.draggable.data('type');
                const label = `Etiqueta ${fieldCount}`;
                const name_html = sanitizeName(label);
                const id_html = name_html;
                field = { id: fieldCount, type, label, name_html, id_html, parentCard: cardId };
                if (type === 'select') field.options = ['Opción 1'];
                if (type === 'radio') field.options = ['Opción 1', 'Opción 2'];
                if (type === 'card') { field.cardTitle = 'Título de la card'; field.cardContent = ''; field.children = []; }
                if (type === 'table') { field.columns = [{ colId: generateColId(), name: 'Columna 1', type: 'text', options: [] }, { colId: generateColId(), name: 'Columna 2', type: 'text', options: [] }]; field.rows = 3; }
                fields.push(field);
            } else if (ui.draggable.hasClass('field-item')) {
                const id = ui.draggable.data('id');
                field = fields.find(f => f.id === id);
                if (field) {
                    delete field.gridPos;
                    fields.forEach(f => {
                        if (f.type === 'card' && f.children) {
                            f.children = f.children.filter(cid => cid !== id);
                        }
                    });
                    field.parentCard = cardId;
                }
            }
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
    // Selección de campo
    $(document).off('click', '.field-item').on('click', '.field-item', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        selectedFieldId = id;
        renderGridDesign();
        renderPropertiesPanel();
    });
}

function renderFieldSelectable(field) {
    let selectedClass = (selectedFieldId && field.id == selectedFieldId) ? ' border-primary border-2 shadow' : '';
    let html = `<div class='field-item${selectedClass}' data-id='${field.id}' draggable='true' style='cursor:pointer; position:relative;'>`;
    html += `<button class='btn btn-xs btn-link text-danger position-absolute top-0 end-0 remove-field-cell' data-id='${field.id}' title='Eliminar campo' style='display:none; z-index:10;'><i class='bi bi-x-circle'></i></button>`;
    html += `<style>.field-item:hover .remove-field-cell{display:inline!important;}</style>`;
    if (field.type === 'card') {
        html += `<span class='badge bg-secondary me-1'>card</span> <span class='field-label'>${field.label}</span>`;
        html += `<div class='card-droppable' data-card-id='${field.id}' style='min-height:40px; border:1px dashed #bbb; margin-top:5px; padding:3px;'>`;
        (field.children || []).forEach(childId => {
            const child = fields.find(f => f.id === childId);
            if (child) {
                html += renderFieldSelectable(child);
            }
        });
        html += `</div>`;
    } else if (field.type === 'url') {
        html += `<label class='form-label'>${field.label}</label>`;
        html += `<a href='${field.default || '#'}' ${field.target_blank ? 'target="_blank" rel="noopener"' : ''} class='form-control'>${field.link_text || field.default || 'Link'}</a>`;
    } else if (field.type === 'textarea') {
        html += `<label class='form-label'>${field.label}</label>`;
        html += `<textarea class='form-control' rows='${field.rows || 3}' cols='${field.cols || 30}' disabled placeholder='${field.placeholder || ''}'>${field.default || ''}</textarea>`;
    } else if (field.type === 'table') {
        html += `<label class='form-label'>${field.label}</label>`;
        html += `<table class='table table-bordered'><thead><tr>`;
        (field.columns || []).forEach(col => { html += `<th>${col.name || ''}</th>`; });
        html += `</tr></thead><tbody>`;
        for (let i = 0; i < (field.rows || 3); i++) {
            html += '<tr>';
            (field.columns || []).forEach(col => {
                if (col.type === 'number') {
                    html += '<td><input type="number" class="form-control"></td>';
                } else if (col.type === 'date') {
                    html += '<td><input type="date" class="form-control"></td>';
                } else if (col.type === 'checkbox') {
                    html += '<td class="text-center"><input type="checkbox" class="form-check-input"></td>';
                } else if (col.type === 'select') {
                    html += '<td><select class="form-select">';
                    (col.options||['Opción 1','Opción 2']).forEach(opt => {
                        html += `<option>${opt}</option>`;
                    });
                    html += '</select></td>';
                } else {
                    html += '<td><input type="text" class="form-control"></td>';
                }
            });
            html += '</tr>';
        }
        html += `</tbody></table>`;
    } else {
        html += `${field.label}`;
    }
    html += `</div>`;
    return html;
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
            if (field && !field.hidden) {
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
                } else if (field.type === 'table') {
                    html += `<label class='form-label'>${field.label}</label>`;
                    html += `<table class='table table-bordered'><thead><tr>`;
                    (field.columns || []).forEach(col => { html += `<th>${col.name || ''}</th>`; });
                    html += `</tr></thead><tbody>`;
                    for (let i = 0; i < (field.rows || 3); i++) {
                        html += '<tr>';
                        (field.columns || []).forEach(col => {
                            if (col.type === 'number') {
                                html += '<td><input type="number" class="form-control"></td>';
                            } else if (col.type === 'date') {
                                html += '<td><input type="date" class="form-control"></td>';
                            } else if (col.type === 'checkbox') {
                                html += '<td class="text-center"><input type="checkbox" class="form-check-input"></td>';
                            } else if (col.type === 'select') {
                                html += '<td><select class="form-select">';
                                (col.options||['Opción 1','Opción 2']).forEach(opt => {
                                    html += `<option>${opt}</option>`;
                                });
                                html += '</select></td>';
                            } else {
                                html += '<td><input type="text" class="form-control"></td>';
                            }
                        });
                        html += '</tr>';
                    }
                    html += `</tbody></table>`;
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
    if (field.type === 'url') {
        html += `<label class='form-label'>${field.label}</label><a href='${field.default || '#'}' ${field.target_blank ? 'target="_blank" rel="noopener"' : ''} class='form-control'>${field.link_text || field.default || 'Link'}</a>`;
    } else if (field.type === 'radio') {
        html += `<label class='form-label'>${field.label}</label><div>`;
        (field.options || []).forEach(opt => {
            html += `<div class='form-check form-check-inline'><input class='form-check-input' type='radio' name='${field.name_html}' value='${opt}' id='${field.id_html}_${opt}'${field.defaultValue === opt ? ' checked' : ''}${extra}><label class='form-check-label' for='${field.id_html}_${opt}'>${opt}</label></div>`;
        });
        html += `</div>`;
    } else if (field.type === 'email') {
        html += `<label class='form-label'>${field.label}</label><input type='email' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'password') {
        html += `<label class='form-label'>${field.label}</label><input type='password' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'number') {
        html += `<label class='form-label'>${field.label}</label><input type='number' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'date') {
        html += `<label class='form-label'>${field.label}</label><input type='date' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'time') {
        html += `<label class='form-label'>${field.label}</label><input type='time' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'file') {
        html += `<label class='form-label'>${field.label}</label><input type='file' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'color') {
        html += `<label class='form-label'>${field.label}</label><input type='color' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'range') {
        html += `<label class='form-label'>${field.label}</label><input type='range' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'tel') {
        html += `<label class='form-label'>${field.label}</label><input type='tel' class='form-control' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'select_multiple') {
        html += `<label class='form-label'>${field.label}</label><select multiple class='form-control' name='${field.name_html}'>`;
        (field.options || []).forEach(opt => {
            html += `<option value='${opt}'>${opt}</option>`;
        });
        html += `</select>`;
    } else if (field.type === 'static') {
        html += `<label class='form-label'>${field.label}</label><p class='form-control-static'>${field.defaultValue || ''}</p>`;
    } else if (field.type === 'input') {
        html += `<label class='form-label' for='${field.id_html}'>${field.label}</label><input type='text' class='form-control' id='${field.id_html}' name='${field.name_html}' placeholder='${field.placeholder || ''}' value='${field.defaultValue || ''}'${extra}>`;
    } else if (field.type === 'textarea') {
        html += `<label class='form-label' for='${field.id_html}'>${field.label}</label><textarea class='form-control' id='${field.id_html}' name='${field.name_html}' rows='${field.rows || 3}' cols='${field.cols || 30}' placeholder='${field.placeholder || ''}'${extra}>${field.default || ''}</textarea>`;
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

function renderPropertiesPanel() {
    if (!selectedFieldId) {
        $('#properties-panel').html('<div class="alert alert-info">Selecciona un campo para editar sus propiedades.</div>');
        return;
    }
    const field = fields.find(f => f.id == selectedFieldId);
    if (!field) {
        $('#properties-panel').html('<div class="alert alert-warning">Campo no encontrado.</div>');
        return;
    }
    let html = `<form id='properties-form'><div class='mb-2'><label class='form-label'>Etiqueta</label><input type='text' class='form-control' name='label' value='${field.label || ''}'></div>`;
    html += `<div class='mb-2'><label class='form-label'>name</label><input type='text' class='form-control' name='name_html' value='${field.name_html || ''}'></div>`;
    html += `<div class='mb-2'><label class='form-label'>id</label><input type='text' class='form-control' name='id_html' value='${field.id_html || ''}'></div>`;
    html += `<div class='mb-2'><label class='form-label'>Placeholder</label><input type='text' class='form-control' name='placeholder' value='${field.placeholder || ''}'></div>`;
    if (field.type === 'url') {
        html += `<div class='mb-2'><label class='form-label'>URL del enlace</label><input type='text' class='form-control' name='default' value='${field.default || ''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Texto del enlace</label><input type='text' class='form-control' name='link_text' value='${field.link_text || ''}'></div>`;
        html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' name='target_blank' ${field.target_blank ? 'checked' : ''}><label class='form-check-label'>Abrir en otra ventana</label></div>`;
    } else if (field.type === 'number' || field.type === 'range') {
        html += `<div class='mb-2'><label class='form-label'>Mínimo</label><input type='number' class='form-control' name='min' value='${field.min || ''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Máximo</label><input type='number' class='form-control' name='max' value='${field.max || ''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Step</label><input type='number' class='form-control' name='step' value='${field.step || 1}'></div>`;
    } else if (field.type === 'file') {
        html += `<div class='mb-2'><label class='form-label'>Aceptar tipos (accept)</label><input type='text' class='form-control' name='accept' value='${field.accept || ''}' placeholder='ej: image/*, .pdf'></div>`;
        html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' name='multiple' ${field.multiple ? 'checked' : ''}><label class='form-check-label'>Permitir múltiples archivos</label></div>`;
    } else if (field.type === 'select_multiple') {
        html += `<div class='mb-2'><label class='form-label'>Opciones (separadas por coma)</label><input type='text' class='form-control' name='options' value='${(field.options || []).join(", ")}'></div>`;
    } else if (field.type === 'tel') {
        html += `<div class='mb-2'><label class='form-label'>Patrón de validación</label><input type='text' class='form-control' name='pattern' value='${field.pattern || ''}' placeholder='ej: [0-9]{3}-[0-9]{3}-[0-9]{4}'></div>`;
    } else if (field.type === 'date' || field.type === 'time') {
        html += `<div class='mb-2'><label class='form-label'>Mínimo</label><input type='${field.type}' class='form-control' name='min' value='${field.min || ''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Máximo</label><input type='${field.type}' class='form-control' name='max' value='${field.max || ''}'></div>`;
    } else if (field.type === 'color') {
        html += `<div class='mb-2'><label class='form-label'>Color por defecto</label><input type='color' class='form-control' name='default' value='${field.default || '#000000'}'></div>`;
    } else if (field.type === 'table') {
        html += `<div class='mb-2'><label class='form-label'>Columnas</label><div id='table-columns-editor'>`;
        (field.columns || []).forEach((col, idx) => {
            html += `<div class='input-group mb-1 align-items-start'>
                <span class='input-group-text handle' style='cursor:move'><i class='bi bi-list'></i></span>
                <input type='text' class='form-control table-col-name' data-colid='${col.colId}' value='${col.name || ''}' placeholder='Nombre columna'>
                <select class='form-select table-col-type' data-colid='${col.colId}'>
                    <option value='text' ${col.type === 'text' ? 'selected' : ''}>Texto</option>
                    <option value='number' ${col.type === 'number' ? 'selected' : ''}>Número</option>
                    <option value='date' ${col.type === 'date' ? 'selected' : ''}>Fecha</option>
                    <option value='checkbox' ${col.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                    <option value='select' ${col.type === 'select' ? 'selected' : ''}>Select</option>
                </select>`;
            if (col.type === 'select') {
                html += `<input type='text' class='form-control table-col-options' data-colid='${col.colId}' placeholder='Opciones (coma)' value='${(col.options||[]).join(", ") || ''}' style='max-width:160px;'>`;
            }
            html += `<button class='btn btn-danger btn-remove-col' data-colid='${col.colId}' type='button'><i class='bi bi-x'></i></button>
            </div>`;
        });
        html += `</div><button type='button' class='btn btn-outline-primary btn-sm w-100' id='btn-add-col'><i class='bi bi-plus'></i> Agregar columna</button></div>`;
        html += `<div class='mb-2'><label class='form-label'>Filas iniciales</label><input type='number' class='form-control' min='1' max='20' name='rows' value='${field.rows || 3}'></div>`;
    }
    html += `<div class='mb-2'><label class='form-label'>Valor por defecto</label><input type='text' class='form-control' name='default' value='${field.default || ''}'></div>`;
    if (field.type === 'textarea') {
        html += `<div class='mb-2'><label class='form-label'>Filas (rows)</label><input type='number' min='1' max='20' class='form-control' name='rows' value='${field.rows || 3}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Columnas (cols)</label><input type='number' min='10' max='100' class='form-control' name='cols' value='${field.cols || 30}'></div>`;
    }
    html += `<div class='mb-2'>
        <label class='form-label mb-1'>Opciones de campo</label>
        <div class='form-check form-switch'>
            <input class='form-check-input field-prop-switch' type='checkbox' id='switch-hidden' name='hidden'${field.hidden ? ' checked' : ''}>
            <label class='form-check-label' for='switch-hidden'>Oculto (hidden)</label>
        </div>
        <div class='form-check form-switch'>
            <input class='form-check-input field-prop-switch' type='checkbox' id='switch-disabled' name='disabled'${field.disabled ? ' checked' : ''}>
            <label class='form-check-label' for='switch-disabled'>Deshabilitado (disabled)</label>
        </div>
        <div class='form-check form-switch'>
            <input class='form-check-input field-prop-switch' type='checkbox' id='switch-required' name='required'${field.required ? ' checked' : ''}>
            <label class='form-check-label' for='switch-required'>Requerido (required)</label>
        </div>
    </div>`;
    html += `<button type='submit' class='btn btn-success w-100'>Guardar</button></form>`;
    $('#properties-panel').html(html);
    // Guardar cambios
    $('#properties-form').off('submit').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serializeArray();
        data.forEach(({name, value}) => {
            if (name === 'hidden' || name === 'disabled' || name === 'required') {
                field[name] = $(this).find(`[name='${name}']`).is(':checked');
            } else if (name === 'options') {
                field.options = value.split(',').map(s => s.trim()).filter(Boolean);
            } else if (name === 'rows' || name === 'cols') {
                field[name] = parseInt(value) || (name === 'rows' ? 3 : 30);
            } else if (name === 'min' || name === 'max') {
                field[name] = parseInt(value) || '';
            } else if (name === 'accept') {
                field[name] = value || '';
            } else if (name === 'pattern') {
                field[name] = value || '';
            } else if (name === 'columns') {
                field.columns = value.split(',').map(s => s.trim()).filter(Boolean);
            } else if (name === 'step') {
                field.step = parseInt(value) || 1;
            } else {
                field[name] = value;
            }
        });
        renderGridDesign();
        renderPreview();
        renderPropertiesPanel();
    });
    // Eventos para switches de propiedades:
    $('.field-prop-switch').off('change').on('change', function() {
        const prop = $(this).attr('name');
        field[prop] = $(this).is(':checked');
        renderPreview();
    });
    // Eventos para columnas dinámicas
    setTimeout(() => {
        $('#btn-add-col').off('click').on('click', function() {
            field.columns = field.columns || [];
            field.columns.push({ colId: generateColId(), name: '', type: 'text', options: [] });
            renderPropertiesPanel();
        });
        $('.btn-remove-col').off('click').on('click', function() {
            const colId = $(this).data('colid');
            field.columns = field.columns.filter(col => col.colId !== colId);
            renderPropertiesPanel();
        });
        $('.table-col-name').off('input').on('input', function() {
            const colId = $(this).data('colid');
            const col = field.columns.find(c => c.colId === colId);
            if (col) {
                col.name = $(this).val();
            }
        });
        $('.table-col-type').off('change').on('change', function() {
            const colId = $(this).data('colid');
            const col = field.columns.find(c => c.colId === colId);
            if (col) {
                col.type = $(this).val();
                if (col.type !== 'select') col.options = [];
                renderPropertiesPanel();
            }
        });
        $('.table-col-options').off('input').on('input', function() {
            const colId = $(this).data('colid');
            const col = field.columns.find(c => c.colId === colId);
            if (col) {
                col.options = $(this).val().split(',').map(s => s.trim()).filter(Boolean);
            }
        });
        // Reordenar columnas con drag & drop
        $('#table-columns-editor').sortable({
            handle: '.handle',
            update: function(event, ui) {
                const newOrder = [];
                $('#table-columns-editor .input-group').each(function() {
                    const colId = $(this).find('.table-col-name').data('colid');
                    const col = field.columns.find(c => c.colId === colId);
                    if (col) {
                        newOrder.push(col);
                    }
                });
                field.columns = newOrder;
                renderPropertiesPanel();
            }
        });
    }, 100);
}

// Inicializar panel de propiedades en el div correcto
$(function() {
    $('#properties-panel').length || $('#properties').html('<div id="properties-panel"></div>');
    renderPropertiesPanel();
    if ($('#grid-controls').length === 0) {
        $('#form-design').before("<div id='grid-controls'></div>");
    }
    initPanelDraggables();
    renderGridDesign();
    renderPreview();
    $(document).on('click', '.btn-remove-field', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        fields = fields.filter(f => f.id !== id);
        renderGridDesign();
        renderPreview();
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
    // Mejora UX Drag & Drop: Iluminar celda/card al hacer dragover
    $(document).on('mouseenter', '.grid-cell, .card-droppable', function() {
        if (!$(this).hasClass('ui-droppable')) {
            $(this).droppable({
                accept: '.draggable, .field-item',
                hoverClass: 'drop-hover',
                drop: function(event, ui) {
                    // ... (ya implementado)
                }
            });
        }
    });
    // CSS para la guía visual
    $('<style>').prop('type', 'text/css').html(`
        .drop-hover { box-shadow: 0 0 0 3px #0d6efd !important; border-color: #0d6efd !important; background: #e7f1ff !important; }
        #table-columns-editor .handle { background: #f1f3f4; }
    `).appendTo('head');
});

function generateColId() {
    return 'col_' + Math.random().toString(36).substr(2, 9);
}
</script>
@endpush
