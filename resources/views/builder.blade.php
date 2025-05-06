<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FormBox Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.css" />
    <style>
        body { background: #f8f9fa; }
        #sidebar-menu {
            background: linear-gradient(120deg, #2563eb 70%, #60a5fa 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 270px;
            z-index: 1050;
            box-shadow: 2px 0 16px 0 #2563eb22;
            border-top-right-radius: 22px;
            border-bottom-right-radius: 22px;
            overflow: hidden;
        }
        #sidebar-menu .fw-bold {
            font-size: 1.5rem;
            letter-spacing: 1px;
            color: #fff;
            text-shadow: 0 2px 8px #2563eb33;
            margin-bottom: 8px;
        }
        .builder-toolbar {
            padding: 28px 18px 0 18px !important;
        }
        #add-section {
            background: linear-gradient(90deg, #fff 60%, #dbeafe 100%);
            color: #2563eb;
            border: none;
            font-weight: 700;
            border-radius: 12px;
            box-shadow: 0 2px 8px #2563eb22;
            transition: box-shadow .2s, background .2s;
            margin-bottom: 16px;
        }
        #add-section:hover {
            background: #fff;
            box-shadow: 0 4px 16px #2563eb33;
        }
        #open-widget-modal {
            background: #2563eb;
            color: #fff;
            border: none;
            font-weight: 700;
            border-radius: 12px;
            box-shadow: 0 2px 8px #60a5fa44;
            margin-bottom: 20px;
        }
        #open-widget-modal:hover {
            background: #1d4ed8;
            color: #fff;
        }
        .sidebar-instructions {
            background: #1e40af33;
            border-radius: 10px;
            padding: 16px 12px;
            margin-top: 22px;
            color: #e0e7ef;
            font-size: 1.05rem;
        }
        .sidebar-instructions li { margin-bottom: 4px; }
        #main-builder-canvas { margin-left: 270px; min-height: 100vh; padding: 40px 32px 32px 32px; background: #f5f7fa; }
        .elementor-section {
            border: 2px dashed #2563eb;
            background: #fff;
            margin-bottom: 32px;
            padding: 24px 12px 12px 12px;
            position: relative;
            border-radius: 18px;
            box-shadow: 0 4px 24px #2563eb0a;
        }
        .elementor-section.selected { box-shadow: 0 0 0 3px #60a5fa; }
        .elementor-section .btn-remove-section {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
        }
        .elementor-column {
            min-height: 70px;
            border: 1.5px dashed #60a5fa;
            background: #f8fbff;
            margin-bottom: 8px;
            position: relative;
            display: inline-block;
            vertical-align: top;
            width: 100%;
            border-radius: 12px;
            margin-right: 8px;
            padding: 12px 6px 18px 6px;
        }
        .elementor-column .btn-remove-column {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
        }
        .elementor-widget {
            background: #f1f5f9;
            border: 1.5px solid #a5b4fc;
            border-radius: 10px;
            margin-bottom: 14px;
            padding: 16px 16px 16px 44px;
            cursor: grab;
            transition: border .2s, box-shadow .2s;
            position: relative;
            box-shadow: 0 2px 8px #2563eb15;
            min-height: 56px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .elementor-widget.selected {
            border: 2.5px solid #2563eb;
            background: #e0e7ff;
            box-shadow: 0 0 0 2px #2563eb33;
        }
        .elementor-widget .btn-remove-widget {
            position: absolute;
            left: 6px;
            top: 6px;
            z-index: 20;
            padding: 0 6px;
            background: #fee2e2;
            border-radius: 6px;
            color: #be123c;
            border: none;
        }
        .elementor-widget .btn-remove-widget:hover { background: #fecaca; color: #991b1b; }
        .elementor-widget .widget-preview {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .elementor-widget input,
        .elementor-widget textarea,
        .elementor-widget select {
            background: #fff;
            border: 1px solid #c7d2fe;
            border-radius: 7px;
            padding: 7px 10px;
            width: 100%;
            font-size: 1rem;
            color: #222;
            box-shadow: none;
            outline: none;
        }
        .elementor-widget input:focus,
        .elementor-widget textarea:focus,
        .elementor-widget select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px #60a5fa33;
        }
        .elementor-widget label {
            font-weight: 500;
            color: #2563eb;
            margin-bottom: 0;
            margin-right: 8px;
            min-width: 90px;
        }
        .sortable-placeholder {
            border: 2px dashed #2563eb !important;
            background: #dbeafe !important;
            min-height: 40px;
            border-radius: 8px;
            margin-bottom: 14px;
        }
        .highlight-drop {
            background-color: #e0f2fe !important;
            border: 2px dashed #0ea5e9 !important;
            transition: all 0.2s ease;
        }
        .sortable-widgets {
            min-height: 30px;
            padding: 5px;
            transition: all 0.2s ease;
        }
        @media (max-width: 991px) {
            #sidebar-menu { width:100vw; border-radius:0; }
            #main-builder-canvas { margin-left:0; padding:16px; }
            .properties-panel { width:100vw; left:0; right:0; top:auto; bottom:0; min-height:auto; border-left:none; border-top:1px solid #e5e5e5; }
        }
    </style>
</head>
<body>
    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" style="z-index:1100;">
        <div class="container-fluid py-2">
            <span class="navbar-brand fw-bold text-primary"><i class="bi bi-ui-checks-grid"></i> FormBox</span>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" title="Guardar"><i class="bi bi-save"></i></button>
                <button class="btn btn-outline-secondary" title="Vista previa" id="btn-preview"><i class="bi bi-eye"></i></button>
                <button class="btn btn-outline-success" title="Exportar ZIP"><i class="bi bi-file-earmark-zip"></i></button>
                <button class="btn btn-outline-warning" title="Deshacer"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </nav>
    <!-- Sidebar minimalista -->
    <div id="sidebar-menu">
        <div class="d-flex flex-column h-100 align-items-center pt-4">
            <button id="add-section" class="btn btn-primary mb-3 w-100 d-flex flex-column align-items-center justify-content-center" title="Agregar sección" style="font-size:1.3rem;">
                <i class="bi bi-layout-three-columns mb-1" style="font-size:2rem;"></i>
                <span style="font-size:0.95rem; font-weight:500;">Agregar sección</span>
            </button>
            <button id="open-widget-modal" class="btn btn-outline-primary mb-3 w-100" title="Agregar campo"><i class="bi bi-plus-circle"></i></button>
            <div class="sidebar-instructions mt-auto mb-3 px-2 text-center">
                <i class="bi bi-info-circle"></i>
                <ul class="list-unstyled small mt-2">
                    <li>Arrastra campos al canvas</li>
                    <li>Haz clic para editar</li>
                    <li>Guarda tu formulario</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Canvas central -->
    <main id="main-builder-canvas">
        <div class="container-fluid">
            <div id="sections-list"></div>
            <div id="empty-builder" class="text-center text-muted mt-5" style="display:none;">
                <i class="bi bi-ui-checks-grid display-1"></i>
                <div class="fs-4 mt-3">Arrastra o agrega secciones y campos para comenzar</div>
            </div>
        </div>
    </main>
    <!-- Panel de propiedades flotante -->
    <div id="properties-panel" class="modal fade" tabindex="-1" style="z-index:1200;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-sliders"></i> Propiedades del campo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="properties-content">
                    <!-- Aquí se cargan dinámicamente los controles de propiedades -->
                </div>
                <div class="modal-footer">
                    <!-- El botón guardar ahora se incluye en el form dinámico, no aquí -->
                </div>
            </div>
        </div>
    </div>
    <!-- Widget Modal (para agregar campos) -->
    <div class="modal fade" id="widgetModal" tabindex="-1" aria-labelledby="widgetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="widgetModalLabel">Agregar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se listan los tipos de widgets disponibles -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Vista Previa -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="previewModalLabel"><i class="bi bi-eye"></i> Vista previa del formulario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body bg-light" id="preview-content">
                    <!-- Aquí se renderiza el formulario -->
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.js"></script>
    <script src="/formbox/panel_properties.js"></script>
    <script>
        // --- Estado global y helpers ---
        window.sections = window.sections || [];
        window.widgetTypes = window.widgetTypes || {
            text: 'Campo de texto', textarea: 'Área de texto', select: 'Selección', switch: 'Interruptor', checkbox: 'Casilla', button: 'Botón', date: 'Fecha', file: 'Archivo', email: 'Email', number: 'Número', password: 'Password', color: 'Color', range: 'Rango', radio: 'Radio', static: 'Texto/HTML', card: 'Card'
        };
        window.widgetIcons = {
            text: 'bi-fonts', textarea: 'bi-card-text', select: 'bi-list', switch: 'bi-toggle-on', checkbox: 'bi-check2-square', button: 'bi-box-arrow-in-right', date: 'bi-calendar', file: 'bi-paperclip', email: 'bi-envelope', number: 'bi-123', password: 'bi-key', color: 'bi-palette', range: 'bi-sliders', radio: 'bi-record-circle', static: 'bi-type', card: 'bi-card-image'
        };

        // Renderizar secciones y columnas (estructura restaurada y drag & drop funcional)
        function renderSections() {
            const $list = $('#sections-list');
            $list.empty();
            if (window.sections.length === 0) {
                $('#empty-builder').show();
                return;
            }
            $('#empty-builder').hide();
            window.sections.forEach((section, sidx) => {
                let sectionHtml = `<div class='elementor-section' data-sidx='${sidx}'>`;
                sectionHtml += `<button class='btn btn-danger btn-sm btn-remove-section' title='Eliminar sección' data-sidx='${sidx}'><i class='bi bi-x'></i></button>`;
                sectionHtml += `<div class='row'>`;
                section.columns.forEach((column, cidx) => {
                    sectionHtml += `<div class='col elementor-column' style='width:${100/section.columns.length}%;display:inline-block;vertical-align:top;' data-sidx='${sidx}' data-cidx='${cidx}'>`;
                    sectionHtml += `<button class='btn btn-danger btn-sm btn-remove-column' title='Eliminar columna' data-sidx='${sidx}' data-cidx='${cidx}'><i class='bi bi-x'></i></button>`;
                    sectionHtml += `<div class='widgets-list sortable-widgets' data-sidx='${sidx}' data-cidx='${cidx}'>`;
                    column.widgets.forEach((widget, widx) => {
                        sectionHtml += `<div class='elementor-widget' data-sidx='${sidx}' data-cidx='${cidx}' data-widx='${widx}'>`;
                        sectionHtml += `<button class='btn btn-danger btn-sm btn-remove-widget' title='Eliminar campo' data-sidx='${sidx}' data-cidx='${cidx}' data-widx='${widx}'><i class='bi bi-x'></i></button>`;
                        sectionHtml += `<div class='widget-preview'>`;
                        sectionHtml += `<i class='bi ${window.widgetIcons ? (window.widgetIcons[widget.type] || 'bi-box') : 'bi-box'} me-2'></i>`;
                        sectionHtml += `<span>${widget.label || window.widgetTypes[widget.type] || widget.type}</span>`;
                        sectionHtml += `</div>`;
                        sectionHtml += `</div>`;
                    });
                    sectionHtml += `</div>`; // widgets-list
                    sectionHtml += `</div>`; // col
                });
                sectionHtml += `<div class='mt-2'><button class='btn btn-outline-primary btn-sm btn-add-column' data-sidx='${sidx}'><i class='bi bi-plus-square'></i> Agregar columna</button></div>`;
                sectionHtml += `</div>`; // row
                sectionHtml += `</div>`; // section
                $list.append(sectionHtml);
            });
            // Drag & drop
            $('.sortable-widgets').sortable({
                connectWith: '.sortable-widgets',
                handle: '.widget-preview',
                placeholder: 'widget-placeholder',
                update: function(event, ui) {
                    sincronizarModeloDeDatos();
                }
            }).disableSelection();
            actualizarIndicesDOM();
        }

        // Sincronizar modelo de datos con el DOM (mejorada: re-renderiza para alinear DOM y modelo)
        function sincronizarModeloDeDatos() {
            let newSections = [];
            $('.elementor-section').each(function(sidx) {
                let newSection = { columns: [] };
                $(this).find('.elementor-column').each(function(cidx) {
                    let newColumn = { widgets: [] };
                    $(this).find('.elementor-widget').each(function(widx) {
                        const sidxData = $(this).data('sidx');
                        const cidxData = $(this).data('cidx');
                        const widxData = $(this).data('widx');
                        if (window.sections[sidxData] && window.sections[sidxData].columns[cidxData] && window.sections[sidxData].columns[cidxData].widgets[widxData]) {
                            let widget = window.sections[sidxData].columns[cidxData].widgets[widxData];
                            newColumn.widgets.push({...widget});
                        }
                    });
                    newSection.columns.push(newColumn);
                });
                newSections.push(newSection);
            });
            window.sections = newSections;
            renderSections(); // <-- Esto asegura que DOM y modelo estén alineados
            if (typeof renderPreview === 'function') {
                renderPreview();
            }
        }

        // Sincroniza los data-attributes de los widgets con el modelo
        function actualizarIndicesDOM() {
            window.sections.forEach((section, sidx) => {
                if (!section.columns) return;
                section.columns.forEach((column, cidx) => {
                    if (!column.widgets) return;
                    column.widgets.forEach((widget, widx) => {
                        // Busca el widget en el DOM por clase y label (más robusto: podrías usar un id único si lo tienes)
                        const $widgets = $(".elementor-widget").filter(function() {
                            return $(this).data('sidx') === sidx && $(this).data('cidx') === cidx;
                        });
                        // Si hay más de uno, asigna por orden
                        $widgets.eq(widx).attr({'data-sidx': sidx, 'data-cidx': cidx, 'data-widx': widx});
                    });
                });
            });
        }

        // --- Acciones UI ---
        $(function() { 
            renderSections();

            // Agregar sección con SweetAlert
            $(document).off('click', '#add-section').on('click', '#add-section', function() {
                Swal.fire({
                    title: '¿Cuántas columnas quieres en esta sección?',
                    input: 'range',
                    inputAttributes: { min: 1, max: 6, step: 1 },
                    inputValue: 1,
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (value) => parseInt(value)
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        createSection(parseInt(result.value));
                        Swal.fire('¡Sección agregada!', '', 'success');
                    }
                });
            });

            // Agregar columna
            $(document).off('click', '.btn-add-column').on('click', '.btn-add-column', function(e) {
                e.preventDefault();
                const sidx = $(this).data('sidx');
                window.sections[sidx].columns.push({ widgets: [] });
                renderSections();
            });

            // Eliminar sección
            $(document).off('click', '.btn-remove-section').on('click', '.btn-remove-section', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const sidx = $(this).data('sidx');
                window.sections.splice(sidx, 1);
                renderSections();
            });

            // Eliminar columna
            $(document).off('click', '.btn-remove-column').on('click', '.btn-remove-column', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const sidx = $(this).data('sidx');
                const cidx = $(this).data('cidx');
                window.sections[sidx].columns.splice(cidx, 1);
                if (window.sections[sidx].columns.length === 0) window.sections.splice(sidx, 1);
                renderSections();
            });

            // Eliminar widget
            $(document).off('click', '.btn-remove-widget').on('click', '.btn-remove-widget', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const sidx = $(this).data('sidx');
                const cidx = $(this).data('cidx');
                const widx = $(this).data('widx');
                window.sections[sidx].columns[cidx].widgets.splice(widx, 1);
                renderSections();
            });

            // Abrir modal de widgets, pero si no hay secciones, mostrar SweetAlert
            $(document).off('click', '#open-widget-modal').on('click', '#open-widget-modal', function() {
                if (!window.sections || window.sections.length === 0) {
                    Swal.fire('Primero debes agregar una sección antes de añadir campos.', '', 'info');
                    return;
                }
                renderWidgetModal();
                new bootstrap.Modal(document.getElementById('widgetModal')).show();
            });

            // Agregar widget a columna seleccionada (o primera si ninguna)
            $(document).off('click', '.btn-modal-widget').on('click', '.btn-modal-widget', function(e) {
                e.preventDefault();
                let $col = $('.elementor-column.selected');
                if ($col.length === 0) $col = $('.elementor-column').first();
                if ($col.length === 0) return Swal.fire('Primero debes agregar una sección y columna.', '', 'info');
                const type = $(this).data('widget');
                const sidx = $col.data('sidx');
                const cidx = $col.data('cidx');
                window.sections[sidx].columns[cidx].widgets.push({ type });
                renderSections();
                bootstrap.Modal.getInstance(document.getElementById('widgetModal')).hide();
            });

            // Selección visual y abrir propiedades
            $('#sections-list').on('click', '.elementor-widget', function(e) {
                e.stopPropagation();
                $('.elementor-widget').removeClass('selected');
                $(this).addClass('selected');
                const sidx = $(this).data('sidx');
                const cidx = $(this).data('cidx');
                const widx = $(this).data('widx');
                let widget = window.sections[sidx]?.columns[cidx]?.widgets[widx];
                if(widget) {
                    renderWidgetPropertiesPanel(widget, sidx, cidx, widx);
                    // Mostrar el modal correctamente usando Bootstrap
                    var modal = new bootstrap.Modal(document.getElementById('properties-panel'));
                    modal.show();
                }
            });

            // Guardar propiedades del widget
            $(document).off('submit', '#widget-properties-form').on('submit', '#widget-properties-form', function(e) {
                e.preventDefault();
                guardarPropiedadesWidget();
                var modalEl = document.getElementById('properties-panel');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) modal.hide();
                renderSections();
            });

            // Conectar el botón de vista previa ORIGINAL
            $(document).off('click', '#btn-preview').on('click', '#btn-preview', function() {
                renderPreview();
                new bootstrap.Modal(document.getElementById('previewModal')).show();
            });

            // Renderiza el formulario actual en modo solo lectura en el modal
            function renderPreview() {
                let html = '<form class="p-2">';
                window.sections.forEach(section => {
                    html += '<div class="row mb-4">';
                    section.columns.forEach(column => {
                        html += '<div class="col">';
                        column.widgets.forEach(widget => {
                            if(widget.hidden) return;
                            html += '<div class="mb-3">';
                            if(widget.label) html += `<label class='form-label fw-semibold mb-1'>${widget.label}</label>`;
                            switch(widget.type) {
                                case 'text':
                                    html += `<input type='text' class='form-control' placeholder='${widget.placeholder||''}' ${widget.disabled ? 'disabled' : ''} />`;
                                    break;
                                case 'textarea':
                                    html += `<textarea class='form-control' placeholder='${widget.placeholder||''}' ${widget.disabled ? 'disabled' : ''}></textarea>`;
                                    break;
                                case 'select':
                                    html += `<select class='form-select' ${widget.disabled ? 'disabled' : ''}>`;
                                    (widget.options||[]).forEach(opt => { html += `<option>${opt}</option>`; });
                                    html += '</select>';
                                    break;
                                case 'checkbox':
                                    html += `<div class='form-check'><input class='form-check-input' type='checkbox' ${widget.disabled ? 'disabled' : ''}><label class='form-check-label ms-2'>${widget.label||''}</label></div>`;
                                    break;
                                case 'switch':
                                    html += `<div class='form-check form-switch'><input class='form-check-input' type='checkbox' role='switch' ${widget.disabled ? 'disabled' : ''}><label class='form-check-label ms-2'>${widget.label||''}</label></div>`;
                                    break;
                                case 'radio':
                                    (widget.options||[]).forEach(opt => {
                                        html += `<div class='form-check'><input class='form-check-input' type='radio' name='${widget.name||''}' ${widget.disabled ? 'disabled' : ''}><label class='form-check-label ms-2'>${opt}</label></div>`;
                                    });
                                    break;
                                case 'button':
                                    html += `<button class='btn btn-primary w-100' type='button' ${widget.disabled ? 'disabled' : ''}>${widget.label||'Botón'}</button>`;
                                    break;
                                case 'date':
                                    html += `<input type='date' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'file':
                                    html += `<input type='file' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'email':
                                    html += `<input type='email' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'number':
                                    html += `<input type='number' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'password':
                                    html += `<input type='password' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'color':
                                    html += `<input type='color' class='form-control form-control-color' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'range':
                                    html += `<input type='range' class='form-range' ${widget.disabled ? 'disabled' : ''}/>`;
                                    break;
                                case 'static':
                                    html += `<div class='form-text text-muted'>${widget.label||''}</div>`;
                                    break;
                                default:
                                    html += `<input type='text' class='form-control' ${widget.disabled ? 'disabled' : ''}/>`;
                            }
                            html += '</div>';
                        });
                        html += '</div>';
                    });
                    html += '</div>';
                });
                html += '</form>';
                $('#preview-content').html(html);
            }
        });

        // Crear sección con el número de columnas seleccionado
        function createSection(columns) {
            let cols = [];
            for (let i = 0; i < columns; i++) { cols.push({ widgets: [] }); }
            window.sections.push({ columns: cols });
            renderSections();
        }

        // Renderizar opciones de widgets en el modal
        function renderWidgetModal() {
            const $body = $('#widgetModal .modal-body');
            $body.empty();
            Object.keys(window.widgetTypes).forEach(type => {
                $body.append(`<button class="btn btn-outline-primary w-100 mb-2 btn-modal-widget" data-widget="${type}"><i class="bi ${window.widgetIcons[type] || 'bi-box'}"></i> ${window.widgetTypes[type]}</button>`);
            });
        }
    </script>
</body>
</html>
