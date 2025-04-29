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
    <div id="sidebar-menu" style="background: linear-gradient(120deg, #2563eb 70%, #60a5fa 100%);">
        <div class="d-flex flex-column h-100">
            <div class="p-3 border-bottom"><span class="fw-bold">FormBox</span></div>
            <div class="builder-toolbar" style="padding:28px 18px 0 18px;">
                <button id="add-section" class="btn btn-light text-primary w-100 mb-3 fw-bold" style="background: linear-gradient(90deg, #fff 60%, #dbeafe 100%); border: none; font-weight: 700; border-radius: 12px; box-shadow: 0 2px 8px #2563eb22; transition: box-shadow .2s, background .2s; margin-bottom: 16px;"><i class="bi bi-plus-circle"></i> Agregar sección</button>
                <button id="open-widget-modal" class="btn btn-outline-light w-100 mb-2"><i class="bi bi-plus-square"></i> Agregar widget</button>
                <div class="text-white-50 small mt-3">
                    <ul style="padding-left:18px;">
                        <li>1. Agrega una sección (fila)</li>
                        <li>2. Selecciona una columna</li>
                        <li>3. Haz clic en Agregar widget</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de widgets -->
    <div class="modal fade" id="widgetModal" tabindex="-1" aria-labelledby="widgetModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="widgetModalLabel">Selecciona un widget</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" id="widget-modal-list">
              <!-- Aquí se llenan los widgets -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="main-builder-canvas">
        <div id="sections-list"></div>
        <div id="empty-builder" class="text-center text-secondary" style="opacity:.7;">
            <i class="bi bi-hand-index-thumb fs-1"></i><br>
            Agrega una sección para comenzar a construir tu formulario
        </div>
    </div>
    <div class="properties-panel" id="properties-panel">
        <div class="fw-bold mb-2">Propiedades del elemento</div>
        <div id="properties-content"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css">
    <script>
    window.sections = window.sections || [];
    window.widgetTypes = window.widgetTypes || {
        text: 'Campo de texto', textarea: 'Área de texto', select: 'Selecciona una opción', switch: 'Interruptor', checkbox: 'Casilla', button: 'Botón', date: 'Fecha', file: 'Archivo', email: 'Email', number: 'Número', password: 'Password', color: 'Color', range: 'Rango', radio: 'Radio', static: 'Texto/HTML', card: 'Card'
    };
    window.widgetIcons = {
        text: 'bi-fonts', textarea: 'bi-card-text', select: 'bi-list', switch: 'bi-toggle-on', checkbox: 'bi-check2-square', button: 'bi-box-arrow-in-right', date: 'bi-calendar', file: 'bi-paperclip', email: 'bi-envelope', number: 'bi-123', password: 'bi-key', color: 'bi-palette', range: 'bi-sliders', radio: 'bi-record-circle', static: 'bi-type', card: 'bi-card-image'
    };

    let lastDraggedWidget = null;

    $(document).ready(function() {
        renderSections();
        
        $(document).on('mousedown', '.elementor-widget', function(e) {
            lastDraggedWidget = {
                sidx: $(this).data('sidx'),
                cidx: $(this).data('cidx'),
                widx: $(this).data('widx'),
                type: window.sections[$(this).data('sidx')]?.columns[$(this).data('cidx')]?.widgets[$(this).data('widx')]?.type || 'text'
            };
            console.log('Widget arrastrado:', lastDraggedWidget);
        });
        
        $(document).off('click', '#add-section').on('click', '#add-section', function() {
            Swal.fire({
                title: '¿Cuántas columnas quieres en esta fila?',
                input: 'range',
                inputAttributes: { min: 1, max: 6, step: 1 },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                preConfirm: (value) => parseInt(value)
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const numCols = parseInt(result.value);
                    let cols = [];
                    for (let i = 0; i < numCols; i++) { cols.push({ widgets: [] }); }
                    window.sections.push({ columns: cols });
                    renderSections();
                }
            });
        });
        $(document).off('click', '.btn-add-column').on('click', '.btn-add-column', function() {
            const sidx = $(this).data('sidx');
            window.sections[sidx].columns.push({ widgets: [] });
            renderSections();
        });
        $(document).off('click', '.btn-remove-section').on('click', '.btn-remove-section', function(e) {
            e.stopPropagation();
            const sidx = $(this).data('sidx');
            window.sections.splice(sidx, 1);
            renderSections();
        });
        $(document).off('click', '.btn-remove-column').on('click', '.btn-remove-column', function(e) {
            e.stopPropagation();
            const sidx = $(this).data('sidx');
            const cidx = $(this).data('cidx');
            window.sections[sidx].columns.splice(cidx, 1);
            if (window.sections[sidx].columns.length === 0) window.sections.splice(sidx, 1);
            renderSections();
        });
        $(document).off('click', '.btn-remove-widget').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const sidx = $(this).data('sidx');
            const cidx = $(this).data('cidx');
            const widx = $(this).data('widx');
            window.sections[sidx].columns[cidx].widgets.splice(widx, 1);
            renderSections();
        });
        // Modal widgets
        $('#open-widget-modal').on('click', function() {
            renderWidgetModal();
            new bootstrap.Modal(document.getElementById('widgetModal')).show();
        });
        $(document).on('click', '.btn-modal-widget', function() {
            // Permitir agregar widget a la primera columna si no hay ninguna seleccionada
            let $col = $('.elementor-column.selected');
            if ($col.length === 0) $col = $('.elementor-column').first();
            if ($col.length === 0) {
                Swal.fire('Primero debes agregar una sección y al menos una columna.');
                return;
            }
            const type = $(this).data('widget');
            const sidx = $col.data('sidx');
            const cidx = $col.data('cidx');
            window.sections[sidx].columns[cidx].widgets.push({ type });
            renderSections();
            bootstrap.Modal.getInstance(document.getElementById('widgetModal')).hide();
        });
        // Selección visual
        $(document).on('click', '.elementor-section', function(e) {
            e.stopPropagation();
            $('.elementor-section').removeClass('selected');
            $(this).addClass('selected');
            $('#properties-panel').addClass('active');
            $('#properties-content').html('<div>Propiedades de la sección (próximamente editable)</div>');
        });
        $(document).on('click', '.elementor-column', function(e) {
            e.stopPropagation();
            $('.elementor-column').removeClass('selected');
            $(this).addClass('selected');
            $('#properties-panel').addClass('active');
            $('#properties-content').html('<div>Propiedades de la columna (próximamente editable)</div>');
        });
        $(document).on('click', '.elementor-widget', function(e) {
            e.stopPropagation();
            $('.elementor-widget').removeClass('selected');
            $(this).addClass('selected');
            $('#properties-panel').addClass('active');
            $('#properties-content').html('<div>Propiedades del widget (próximamente editable)</div>');
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.elementor-section, .elementor-column, .elementor-widget, #properties-panel, #widgetModal').length) {
                $('.selected').removeClass('selected');
                $('#properties-panel').removeClass('active');
            }
        });
    });
    
    function renderSections() {
        const $list = $('#sections-list');
        $list.empty();
        if (window.sections.length === 0) {
            $('#empty-builder').show();
        } else {
            $('#empty-builder').hide();
            window.sections.forEach((section, sidx) => {
                let sectionHtml = `<div class='elementor-section' data-sidx='${sidx}'>`;
                sectionHtml += `<button class='btn btn-danger btn-sm btn-remove-section' title='Eliminar sección' data-sidx='${sidx}'><i class='bi bi-x'></i></button>`;
                sectionHtml += `<div class='row'>`;
                section.columns.forEach((col, cidx) => {
                    sectionHtml += `<div class='col elementor-column' style='width:${100/section.columns.length}%;display:inline-block;vertical-align:top;' data-sidx='${sidx}' data-cidx='${cidx}'>`;
                    sectionHtml += `<button class='btn btn-danger btn-sm btn-remove-column' title='Eliminar columna' data-sidx='${sidx}' data-cidx='${cidx}'><i class='bi bi-x'></i></button>`;
                    sectionHtml += `<div class='widgets-list sortable-widgets' data-sidx='${sidx}' data-cidx='${cidx}'>`;
                    col.widgets.forEach((widget, widx) => {
                        sectionHtml += `<div class='elementor-widget' data-sidx='${sidx}' data-cidx='${cidx}' data-widx='${widx}'>`;
                        sectionHtml += `<button class='btn btn-remove-widget' title='Eliminar widget' data-sidx='${sidx}' data-cidx='${cidx}' data-widx='${widx}'><i class='bi bi-x'></i></button>`;
                        sectionHtml += `<div class='widget-preview'>`;
                        if(widget.type === 'text') {
                            sectionHtml += `<label><i class='bi bi-fonts'></i> Texto</label><input type='text' class='form-control' disabled placeholder='Campo de texto'>`;
                        } else if(widget.type === 'textarea') {
                            sectionHtml += `<label><i class='bi bi-card-text'></i> Área</label><textarea class='form-control' rows='2' disabled placeholder='Área de texto'></textarea>`;
                        } else if(widget.type === 'select') {
                            sectionHtml += `<label><i class='bi bi-list'></i> Selección</label><select class='form-select' disabled><option>Opción 1</option><option>Opción 2</option></select>`;
                        } else if(widget.type === 'checkbox') {
                            sectionHtml += `<label><i class='bi bi-check2-square'></i> Casilla</label><input type='checkbox' class='form-check-input' disabled>`;
                        } else if(widget.type === 'switch') {
                            sectionHtml += `<label><i class='bi bi-toggle-on'></i> Switch</label><input type='checkbox' class='form-check-input' disabled style='accent-color:#2563eb;'>`;
                        } else if(widget.type === 'button') {
                            sectionHtml += `<button class='btn btn-primary btn-sm' disabled><i class='bi bi-box-arrow-in-right'></i> Botón</button>`;
                        } else if(widget.type === 'date') {
                            sectionHtml += `<label><i class='bi bi-calendar'></i> Fecha</label><input type='date' class='form-control' disabled>`;
                        } else if(widget.type === 'file') {
                            sectionHtml += `<label><i class='bi bi-paperclip'></i> Archivo</label><input type='file' class='form-control' disabled>`;
                        } else if(widget.type === 'email') {
                            sectionHtml += `<label><i class='bi bi-envelope'></i> Email</label><input type='email' class='form-control' disabled placeholder='Email'>`;
                        } else if(widget.type === 'number') {
                            sectionHtml += `<label><i class='bi bi-123'></i> Número</label><input type='number' class='form-control' disabled placeholder='Número'>`;
                        } else if(widget.type === 'password') {
                            sectionHtml += `<label><i class='bi bi-key'></i> Password</label><input type='password' class='form-control' disabled placeholder='Password'>`;
                        } else if(widget.type === 'color') {
                            sectionHtml += `<label><i class='bi bi-palette'></i> Color</label><input type='color' class='form-control form-control-color' disabled>`;
                        } else if(widget.type === 'range') {
                            sectionHtml += `<label><i class='bi bi-sliders'></i> Rango</label><input type='range' class='form-range' disabled>`;
                        } else if(widget.type === 'radio') {
                            sectionHtml += `<label><i class='bi bi-record-circle'></i> Radio</label><input type='radio' class='form-check-input' disabled>`;
                        } else if(widget.type === 'static') {
                            sectionHtml += `<span class='text-secondary'><i class='bi bi-type'></i> Texto/HTML estático</span>`;
                        } else if(widget.type === 'card') {
                            sectionHtml += `<div class='card' style='width:100%;background:#fff;border:1px solid #c7d2fe;'><div class='card-body'><h5 class='card-title'><i class='bi bi-card-image'></i> Card</h5><p class='card-text'>Contenido de ejemplo</p></div></div>`;
                        } else {
                            sectionHtml += `<span><i class='bi ${window.widgetIcons[widget.type] || 'bi-box'}'></i> ${window.widgetTypes[widget.type] || widget.type}</span>`;
                        }
                        sectionHtml += `</div>`;
                        sectionHtml += `</div>`;
                    });
                    sectionHtml += `</div></div>`;
                });
                sectionHtml += `</div>`;
                sectionHtml += `<div class='mt-2'><button class='btn btn-outline-primary btn-sm btn-add-column' data-sidx='${sidx}'><i class='bi bi-plus-square'></i> Agregar columna</button></div>`;
                sectionHtml += `</div>`;
                $list.append(sectionHtml);
            });
        }
        
        // Nueva implementación de drag & drop
        initDragAndDrop();
    }

    function sincronizarModeloDeDatos() {
        console.log('Sincronizando datos del modelo...');
        
        // Crear nueva estructura de datos basada en el DOM actual
        let newSections = [];
        
        $('.elementor-section').each(function(sidx) {
            let newSection = { columns: [] };
            
            $(this).find('.elementor-column').each(function(cidx) {
                let newColumn = { widgets: [] };
                
                $(this).find('.elementor-widget').each(function(widx) {
                    // Obtener datos del widget original
                    let originalSidx = $(this).attr('data-sidx');
                    let originalCidx = $(this).attr('data-cidx');
                    let originalWidx = $(this).attr('data-widx');
                    
                    try {
                        // Si existe en el modelo original, copiarlo
                        if (window.sections[originalSidx] && 
                            window.sections[originalSidx].columns[originalCidx] && 
                            window.sections[originalSidx].columns[originalCidx].widgets[originalWidx]) {
                            let widget = window.sections[originalSidx].columns[originalCidx].widgets[originalWidx];
                            newColumn.widgets.push({...widget});
                        } else if (lastDraggedWidget) {
                            newColumn.widgets.push({ type: lastDraggedWidget.type });
                        } else {
                            // Intentar buscar por contenido si los índices no coinciden
                            let foundWidget = false;
                            for (let s = 0; s < window.sections.length; s++) {
                                for (let c = 0; c < window.sections[s].columns.length; c++) {
                                    for (let w = 0; w < window.sections[s].columns[c].widgets.length; w++) {
                                        let currentWidget = window.sections[s].columns[c].widgets[w];
                                        let currentHTML = $(this).html();
                                        if (currentHTML.indexOf(currentWidget.type) > -1) {
                                            newColumn.widgets.push({...currentWidget});
                                            foundWidget = true;
                                            break;
                                        }
                                    }
                                    if (foundWidget) break;
                                }
                                if (foundWidget) break;
                            }
                            
                            // Si no encontramos el widget, determinar tipo por el contenido HTML
                            if (!foundWidget) {
                                let type = 'text';
                                if ($(this).find('.bi-card-text').length > 0) type = 'textarea';
                                if ($(this).find('.bi-list').length > 0) type = 'select';
                                if ($(this).find('.bi-check2-square').length > 0) type = 'checkbox';
                                if ($(this).find('.bi-toggle-on').length > 0) type = 'switch';
                                if ($(this).find('.bi-box-arrow-in-right').length > 0) type = 'button';
                                if ($(this).find('.bi-calendar').length > 0) type = 'date';
                                if ($(this).find('.bi-paperclip').length > 0) type = 'file';
                                if ($(this).find('.bi-envelope').length > 0) type = 'email';
                                if ($(this).find('.bi-123').length > 0) type = 'number';
                                if ($(this).find('.bi-key').length > 0) type = 'password';
                                if ($(this).find('.bi-palette').length > 0) type = 'color';
                                if ($(this).find('.bi-sliders').length > 0) type = 'range';
                                if ($(this).find('.bi-record-circle').length > 0) type = 'radio';
                                if ($(this).find('.bi-type').length > 0) type = 'static';
                                if ($(this).find('.card-title').length > 0) type = 'card';
                                
                                newColumn.widgets.push({ type });
                            }
                        }
                    } catch (error) {
                        console.error("Error sincronizando widget:", error);
                        // En caso de error, agregar un widget de texto como fallback
                        newColumn.widgets.push({ type: 'text' });
                    }
                });
                
                newSection.columns.push(newColumn);
            });
            
            newSections.push(newSection);
        });
        
        // Reemplazar el modelo de datos actual
        window.sections = newSections;
        
        // Actualizar atributos de datos en el DOM
        actualizarIndices();

        // Console debug to help identify issues
        console.log('Modelo actualizado:', JSON.stringify(window.sections));
        
        // Resetear el último widget arrastrado
        lastDraggedWidget = null;
    }

    function initDragAndDrop() {
        console.log('Inicializando drag & drop...');
        
        // Destruir sortables previos
        try {
            $('.sortable-widgets').sortable('destroy');
        } catch(e) {}
        
        // Inicializar sortable en todas las columnas
        $('.sortable-widgets').sortable({
            connectWith: '.sortable-widgets',
            placeholder: 'sortable-placeholder',
            items: '> .elementor-widget',
            cursor: 'move',
            opacity: 0.7,
            revert: true,
            tolerance: 'pointer',
            zIndex: 9999,
            scroll: true,
            delay: 150,
            distance: 5,
            handle: '.widget-preview',
            forceHelperSize: true, 
            forcePlaceholderSize: true,
            helper: function(event, ui) {
                var $clone = $(ui).clone();
                $clone.css('position', 'absolute');
                return $clone;
            },
            start: function(e, ui) {
                ui.placeholder.height(ui.item.height());
                ui.helper.css('z-index', 9999);
                $(this).sortable('refresh');
                $('.sortable-widgets').sortable('refreshPositions');
            },
            over: function(e, ui) {
                $(this).addClass('highlight-drop');
            },
            out: function(e, ui) {
                $(this).removeClass('highlight-drop');
            },
            beforeStop: function(e, ui) {
                // Evitar que se reinicialice durante el drag
                e.stopPropagation();
            },
            receive: function(event, ui) {
                console.log('Widget recibido en nueva columna');
                $(this).removeClass('highlight-drop');
            },
            update: function(event, ui) {
                console.log('Widget actualizado');
                if(ui.sender) {
                    console.log('Widget movido de otra columna');
                } else {
                    console.log('Widget reordenado en la misma columna');
                }
            },
            stop: function(e, ui) {
                $(this).removeClass('highlight-drop');
                // Dar tiempo para que termine la animación
                setTimeout(function() {
                    // Actualizar el modelo de datos
                    sincronizarModeloDeDatos();
                }, 100);
            }
        }).disableSelection();
    }

    function renderWidgetModal() {
        const $list = $('#widget-modal-list');
        $list.empty();
        Object.keys(window.widgetTypes).forEach(type => {
            $list.append(`<div class="col-6 mb-3"><button class="btn btn-outline-primary w-100 btn-modal-widget" data-widget="${type}"><i class="bi ${window.widgetIcons[type] || 'bi-box'}"></i> ${window.widgetTypes[type]}</button></div>`);
        });
    }

    function actualizarIndices() {
        // Recorremos todas las columnas y widgets para actualizar sus índices en el DOM
        $('.elementor-section').each(function(sidx) {
            $(this).attr('data-sidx', sidx);
            $(this).find('.btn-remove-section').attr('data-sidx', sidx);
            $(this).find('.btn-add-column').attr('data-sidx', sidx);
            
            $(this).find('.elementor-column').each(function(cidx) {
                $(this).attr('data-sidx', sidx);
                $(this).attr('data-cidx', cidx);
                $(this).find('.btn-remove-column').attr('data-sidx', sidx);
                $(this).find('.btn-remove-column').attr('data-cidx', cidx);
                $(this).find('.sortable-widgets').attr('data-sidx', sidx);
                $(this).find('.sortable-widgets').attr('data-cidx', cidx);
                
                $(this).find('.elementor-widget').each(function(widx) {
                    $(this).attr('data-sidx', sidx);
                    $(this).attr('data-cidx', cidx);
                    $(this).attr('data-widx', widx);
                    $(this).find('.btn-remove-widget').attr('data-sidx', sidx);
                    $(this).find('.btn-remove-widget').attr('data-cidx', cidx);
                    $(this).find('.btn-remove-widget').attr('data-widx', widx);
                });
            });
        });
    }
    </script>
</body>
</html>
