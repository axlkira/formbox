// === PANEL DE PROPIEDADES: SOLUCIÓN DEFINITIVA ===

// 1. Delegación robusta para selección de WIDGET
$('#sections-list').off('click', '.elementor-widget').on('click', '.elementor-widget', function(e) {
    console.log('*** WIDGET CLICK HANDLER (#sections-list) FIRED! Target:', e.target, ' CurrentTarget:', e.currentTarget); 
    e.stopPropagation(); // Evita que el clic llegue a elementos padres (columna, sección)
    console.log('PANEL: Clic detectado en Widget:', this);

    // Marcar como seleccionado
    $('.elementor-widget, .elementor-column, .elementor-section').removeClass('selected');
    $(this).addClass('selected');
    $('#properties-panel').show().css('right', '0px').addClass('active');
    console.log('PANEL: Panel forzado a ser visible.');

    // Obtener datos del widget del modelo
    const sidx = parseInt($(this).attr('data-sidx'));
    const cidx = parseInt($(this).attr('data-cidx'));
    const widx = parseInt($(this).attr('data-widx'));
    console.log(`PANEL: Buscando widget en índices [${sidx}][${cidx}][${widx}]`); 
    
    // Obtener una copia de los datos actuales del widget
    const currentWidget = window.sections[sidx]?.columns[cidx]?.widgets[widx];
    if (!currentWidget) {
        $('#properties-content').html('<div class="alert alert-danger">No se pudo cargar el widget seleccionado.</div>');
        return;
    }
    
    // Guardar referencia directa al widget
    selectedWidgetRef = currentWidget;
    
    try {
        // --- Generación del HTML del formulario de propiedades ---
        let html = `<form id='widget-properties-form'>`;
        html += `<div class='mb-3'><span class='badge bg-secondary'>Tipo: ${selectedWidgetRef.type}</span></div>`;

        // Propiedades comunes
        html += `<div class='mb-2'><label class='form-label fw-bold'>Nombre (name)</label><input type='text' class='form-control form-control-sm' name='name' value='${selectedWidgetRef.name || ''}' required placeholder='ej: nombre_usuario'></div>`;
        html += `<div class='mb-2'><label class='form-label fw-bold'>ID</label><input type='text' class='form-control form-control-sm' name='id' value='${selectedWidgetRef.id || ''}' placeholder='ej: user_name_id'></div>`;
        html += `<div class='mb-2'><label class='form-label fw-bold'>Etiqueta (label)</label><input type='text' class='form-control form-control-sm' name='label' value='${selectedWidgetRef.label || ''}' placeholder='Texto visible para el usuario'></div>`;
        html += `<div class='mb-2'><label class='form-label fw-bold'>Placeholder</label><input type='text' class='form-control form-control-sm' name='placeholder' value='${selectedWidgetRef.placeholder || ''}'></div>`;
        html += `<div class='mb-2'><label class='form-label fw-bold'>Requerido <input type='checkbox' name='required' ${selectedWidgetRef.required ? 'checked' : ''}></label></div>`;
        html += `<div class='mb-2'><label class='form-label fw-bold'>Deshabilitado <input type='checkbox' name='disabled' ${selectedWidgetRef.disabled ? 'checked' : ''}></label></div>`;
        
        if(selectedWidgetRef.type === 'select' || selectedWidgetRef.type === 'radio') {
            html += `<div class='mb-2'><label class='form-label fw-bold'>Opciones (una por línea)</label><textarea class='form-control' name='options' rows='3'>${(selectedWidgetRef.options||[]).join('\n')}</textarea></div>`;
        }
        
        html += `<button type='submit' class='btn btn-success w-100 mt-2'>Guardar</button></form>`;
        $('#properties-content').html(html);
        console.log('PANEL: HTML generado para propiedades.');

    } catch (error) {
        console.error('PANEL CRITICAL ERROR en el handler del clic:', error); 
        $('#properties-content').html('<div class="alert alert-danger">Error crítico al procesar el clic del widget. Revisa la consola.</div>');
    }
});

// 2. Delegación para GUARDAR propiedades desde el panel
$(document).off('submit', '#widget-properties-form').on('submit', '#widget-properties-form', function(e) {
    e.preventDefault();
    console.log('PANEL: Guardando propiedades...');
    
    if (!selectedWidgetRef) {
        alert('No se pudo encontrar el widget para guardar los cambios.');
        return;
    }
    
    try {
        const $form = $(this);
        
        // Actualizar los datos del widget
        const formData = $form.serializeArray();
        formData.forEach(d => { selectedWidgetRef[d.name] = d.value; });
        selectedWidgetRef.required = $form.find('[name="required"]').is(':checked');
        selectedWidgetRef.disabled = $form.find('[name="disabled"]').is(':checked');
        
        if(selectedWidgetRef.type === 'select' || selectedWidgetRef.type === 'radio') {
            selectedWidgetRef.options = $form.find('[name="options"]').val().split(/\r?\n/).filter(x=>x.trim()!=='');
        }
        
        // Guardar los índices antes de renderizar
        const $selected = $('.elementor-widget.selected');
        const sidx = parseInt($selected.attr('data-sidx'));
        const cidx = parseInt($selected.attr('data-cidx'));
        const widx = parseInt($selected.attr('data-widx'));
        
        renderSections();
        
        // Intentar reseleccionar el widget después de renderizar
        setTimeout(() => {
            try {
                const $widget = $(`.elementor-widget[data-sidx="${sidx}"][data-cidx="${cidx}"][data-widx="${widx}"]`);
                if ($widget.length) {
                    $widget.addClass('selected');
                    $('#properties-panel').addClass('active').show();
                }
            } catch (e) {
                console.error('Error al reseleccionar widget:', e);
            }
        }, 100);
        
        // Mostrar confirmación
        alert('Las propiedades se han guardado correctamente');
        
    } catch (error) {
        console.error('PANEL SAVE CRITICAL ERROR:', error);
        alert('Ocurrió un error al guardar: ' + error.message);
    }
});

// 3. Delegación para selección de COLUMNA (muestra panel básico)
$('#sections-list').off('click', '.elementor-column').on('click', '.elementor-column', function(e) {
    e.stopPropagation();
    console.log('PANEL: Clic detectado en Columna:', this);
    $('.elementor-widget, .elementor-column, .elementor-section').removeClass('selected');
    $(this).addClass('selected');
    $('#properties-panel').show().css('right', '0px').addClass('active');
    $('#properties-content').html('<p class="text-muted">Propiedades de la Columna seleccionada. (Edición futura)</p>');
});

// 4. Delegación para selección de SECCIÓN (muestra panel básico)
$('#sections-list').off('click', '.elementor-section').on('click', '.elementor-section', function(e) {
    e.stopPropagation();
    console.log('PANEL: Clic detectado en Sección:', this);
    $('.elementor-widget, .elementor-column, .elementor-section').removeClass('selected');
    $(this).addClass('selected');
    $('#properties-panel').show().css('right', '0px').addClass('active');
    $('#properties-content').html('<p class="text-muted">Propiedades de la Sección seleccionada. (Edición futura)</p>');
});

// 5. Clic FUERA para deseleccionar y ocultar panel
$(document).off('click.outsidePanel').on('click.outsidePanel', function(e) {
    // Si el clic NO fue dentro del panel, ni sobre un elemento seleccionable, ni en un modal
    if (!$(e.target).closest('#properties-panel, .elementor-widget, .elementor-column, .elementor-section, .btn-modal-widget, #widgetModal, .swal2-container').length) {
        console.log('PANEL: Clic detectado fuera.');
        $('.elementor-widget, .elementor-column, .elementor-section').removeClass('selected');
        $('#properties-panel').removeClass('active').css('right', '-350px');
    }
});

// === FIN SOLUCIÓN DEFINITIVA PANEL ===
