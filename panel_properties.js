// panel_properties.js
// Lógica del panel de propiedades dinámico para FormBox

(function(window, $) {
    // Renderiza el panel de propiedades para el widget seleccionado
    window.renderWidgetPropertiesPanel = function(widget, sidx, cidx, widx) {
        let html = '<form id="widget-properties-form" data-sidx="'+sidx+'" data-cidx="'+cidx+'" data-widx="'+widx+'">';
        html += `<div class='mb-2'><label class='form-label'>ID</label><input type='text' class='form-control' id='prop-id' value='${widget.id||''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Nombre (name)</label><input type='text' class='form-control' id='prop-name' value='${widget.name||''}'></div>`;
        html += `<div class='mb-2'><label class='form-label'>Etiqueta (label)</label><input type='text' class='form-control' id='prop-label' value='${widget.label||''}'></div>`;
        html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-hidden' ${widget.hidden ? 'checked' : ''}><label class='form-check-label' for='prop-hidden'>Ocultar campo</label></div>`;
        html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-disabled' ${widget.disabled ? 'checked' : ''}><label class='form-check-label' for='prop-disabled'>Deshabilitado</label></div>`;
        // Propiedades específicas según el tipo
        switch(widget.type) {
            case 'textarea':
                html += `<div class='mb-2'><label class='form-label'>Filas (rows)</label><input type='number' class='form-control' id='prop-rows' min='1' value='${widget.rows||3}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Placeholder</label><input type='text' class='form-control' id='prop-placeholder' value='${widget.placeholder||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Máximo de caracteres</label><input type='number' class='form-control' id='prop-maxlength' min='1' value='${widget.maxlength||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-readonly' ${widget.readonly ? 'checked' : ''}><label class='form-check-label' for='prop-readonly'>Solo lectura</label></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'text':
                html += `<div class='mb-2'><label class='form-label'>Placeholder</label><input type='text' class='form-control' id='prop-placeholder' value='${widget.placeholder||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Máximo de caracteres</label><input type='number' class='form-control' id='prop-maxlength' min='1' value='${widget.maxlength||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Patrón (pattern)</label><input type='text' class='form-control' id='prop-pattern' value='${widget.pattern||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-readonly' ${widget.readonly ? 'checked' : ''}><label class='form-check-label' for='prop-readonly'>Solo lectura</label></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'email':
                html += `<div class='mb-2'><label class='form-label'>Placeholder</label><input type='text' class='form-control' id='prop-placeholder' value='${widget.placeholder||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Patrón (pattern)</label><input type='text' class='form-control' id='prop-pattern' value='${widget.pattern||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Máximo de caracteres</label><input type='number' class='form-control' id='prop-maxlength' min='1' value='${widget.maxlength||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'password':
                html += `<div class='mb-2'><label class='form-label'>Placeholder</label><input type='text' class='form-control' id='prop-placeholder' value='${widget.placeholder||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Mínimo de caracteres</label><input type='number' class='form-control' id='prop-minlength' min='1' value='${widget.minlength||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Máximo de caracteres</label><input type='number' class='form-control' id='prop-maxlength' min='1' value='${widget.maxlength||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'number':
                html += `<div class='mb-2'><label class='form-label'>Min</label><input type='number' class='form-control' id='prop-min' value='${widget.min||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Max</label><input type='number' class='form-control' id='prop-max' value='${widget.max||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Step</label><input type='number' class='form-control' id='prop-step' value='${widget.step||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'date':
                html += `<div class='mb-2'><label class='form-label'>Fecha mínima</label><input type='date' class='form-control' id='prop-min' value='${widget.min||''}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Fecha máxima</label><input type='date' class='form-control' id='prop-max' value='${widget.max||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'file':
                html += `<div class='mb-2'><label class='form-label'>Tipos permitidos (accept)</label><input type='text' class='form-control' id='prop-accept' placeholder='image/*,.pdf' value='${widget.accept||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-multiple' ${widget.multiple ? 'checked' : ''}><label class='form-check-label' for='prop-multiple'>Permitir múltiples archivos</label></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'color':
                html += `<div class='mb-2'><label class='form-label'>Color inicial</label><input type='color' class='form-control form-control-color' id='prop-value' value='${widget.value||'#000000'}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-disabled' ${widget.disabled ? 'checked' : ''}><label class='form-check-label' for='prop-disabled'>Deshabilitado</label></div>`;
                break;
            case 'range':
                html += `<div class='mb-2'><label class='form-label'>Min</label><input type='number' class='form-control' id='prop-min' value='${widget.min||0}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Max</label><input type='number' class='form-control' id='prop-max' value='${widget.max||100}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Step</label><input type='number' class='form-control' id='prop-step' value='${widget.step||1}'></div>`;
                html += `<div class='mb-2'><label class='form-label'>Valor inicial</label><input type='number' class='form-control' id='prop-value' value='${widget.value||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'radio':
                html += `<div class='mb-2'><label class='form-label'>Opciones (una por línea)</label><textarea class='form-control' id='prop-options' rows='3'>${(widget.options||[]).join('\n')}</textarea></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-inline' ${widget.inline ? 'checked' : ''}><label class='form-check-label' for='prop-inline'>Mostrar en línea (horizontal)</label></div>`;
                html += `<div class='mb-2'><label class='form-label'>Valor por defecto</label><input type='text' class='form-control' id='prop-default' value='${widget.default||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'checkbox':
            case 'switch':
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-inline' ${widget.inline ? 'checked' : ''}><label class='form-check-label' for='prop-inline'>Mostrar en línea (horizontal)</label></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-checked' ${widget.checked ? 'checked' : ''}><label class='form-check-label' for='prop-checked'>Marcado por defecto</label></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            case 'select':
                html += `<div class='mb-2'><label class='form-label'>Opciones (una por línea)</label><textarea class='form-control' id='prop-options' rows='3'>${(widget.options||[]).join('\n')}</textarea></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-multiple' ${widget.multiple ? 'checked' : ''}><label class='form-check-label' for='prop-multiple'>Selección múltiple</label></div>`;
                html += `<div class='mb-2'><label class='form-label'>Tamaño (size)</label><input type='number' class='form-control' id='prop-size' min='1' value='${widget.size||''}'></div>`;
                html += `<div class='form-check mb-2'><input class='form-check-input' type='checkbox' id='prop-required' ${widget.required ? 'checked' : ''}><label class='form-check-label' for='prop-required'>Requerido</label></div>`;
                break;
            // Puedes seguir agregando para otros tipos...
        }
        html += `<button type='submit' class='btn btn-primary btn-sm mt-2 w-100'>Guardar</button>`;
        html += `</form>`;
        $('#properties-content').html(html);
    };

    // Guardar propiedades del widget desde el panel
    window.guardarPropiedadesWidget = function() {
        const $form = $('#widget-properties-form');
        const sidx = parseInt($form.data('sidx'));
        const cidx = parseInt($form.data('cidx'));
        const widx = parseInt($form.data('widx'));
        let widget = window.sections[sidx]?.columns[cidx]?.widgets[widx];
        if (!widget) {
            Swal.fire('Error', 'No se pudo encontrar el widget para guardar los cambios.', 'error');
            return;
        }
        widget.label = $('#prop-label').val();
        widget.id = $('#prop-id').val();
        widget.name = $('#prop-name').val();
        widget.hidden = $('#prop-hidden').is(':checked');
        widget.disabled = $('#prop-disabled').is(':checked');
        // Propiedades específicas
        switch(widget.type) {
            case 'textarea':
                widget.rows = parseInt($('#prop-rows').val()) || 3;
                widget.placeholder = $('#prop-placeholder').val();
                widget.maxlength = parseInt($('#prop-maxlength').val()) || '';
                widget.readonly = $('#prop-readonly').is(':checked');
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'text':
                widget.placeholder = $('#prop-placeholder').val();
                widget.maxlength = parseInt($('#prop-maxlength').val()) || '';
                widget.pattern = $('#prop-pattern').val();
                widget.readonly = $('#prop-readonly').is(':checked');
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'email':
                widget.placeholder = $('#prop-placeholder').val();
                widget.pattern = $('#prop-pattern').val();
                widget.maxlength = parseInt($('#prop-maxlength').val()) || '';
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'password':
                widget.placeholder = $('#prop-placeholder').val();
                widget.minlength = parseInt($('#prop-minlength').val()) || '';
                widget.maxlength = parseInt($('#prop-maxlength').val()) || '';
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'number':
                widget.min = $('#prop-min').val();
                widget.max = $('#prop-max').val();
                widget.step = $('#prop-step').val();
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'date':
                widget.min = $('#prop-min').val();
                widget.max = $('#prop-max').val();
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'file':
                widget.accept = $('#prop-accept').val();
                widget.multiple = $('#prop-multiple').is(':checked');
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'color':
                widget.value = $('#prop-value').val();
                widget.disabled = $('#prop-disabled').is(':checked');
                break;
            case 'range':
                widget.min = $('#prop-min').val();
                widget.max = $('#prop-max').val();
                widget.step = $('#prop-step').val();
                widget.value = $('#prop-value').val();
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'radio':
                widget.options = ($('#prop-options').val() || '').split('\n').map(opt => opt.trim()).filter(opt => opt);
                widget.inline = $('#prop-inline').is(':checked');
                widget.default = $('#prop-default').val();
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'checkbox':
            case 'switch':
                widget.inline = $('#prop-inline').is(':checked');
                widget.checked = $('#prop-checked').is(':checked');
                widget.required = $('#prop-required').is(':checked');
                break;
            case 'select':
                widget.options = ($('#prop-options').val() || '').split('\n').map(opt => opt.trim()).filter(opt => opt);
                widget.multiple = $('#prop-multiple').is(':checked');
                widget.size = parseInt($('#prop-size').val()) || '';
                widget.required = $('#prop-required').is(':checked');
                break;
            // Puedes seguir agregando para otros tipos...
        }
    };
})(window, jQuery);
