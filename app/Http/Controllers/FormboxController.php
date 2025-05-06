<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FormboxController extends Controller
{
    // Descargar archivo Blade
    public function downloadBlade(Request $request)
    {
        $sections = json_decode($request->input('sections'), true);
        $blade = $this->generateBlade($sections);
        $filename = 'formulario.blade.php';
        return Response::make($blade, 200, [
            'Content-Type' => 'text/x-php',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    // Descargar archivo HTML (nuevo método seguro, sin afectar Blade)
    public function downloadHtml(Request $request)
    {
        $sections = json_decode($request->input('sections'), true);
        $html = $this->generatePureHtml($sections);
        $filename = 'formulario.html';
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    // Guardar formulario desde builder (AJAX)
    public function save(Request $request)
    {
        $name = $request->input('name');
        $sections = $request->input('sections');
        if (!$name || !$sections) {
            return response()->json(['message' => 'Nombre y estructura del formulario requeridos.'], 400);
        }
        // Guardar como archivo temporal en storage/app/forms
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) . '_' . date('Ymd_His') . '.json';
        $path = storage_path('app/forms');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/' . $filename, $sections);
        return response()->json(['message' => 'Formulario guardado correctamente.', 'file' => $filename], 200);
    }

    // Renderiza el formulario como Blade
    private function generateBlade($sections)
    {
        $html = "<form method=\"POST\" action=\"{{ route('form.submit') }}\">\n    @csrf\n";
        foreach ($sections as $section) {
            $html .= "    <div class='row mb-4'>\n";
            foreach ($section['columns'] as $column) {
                $html .= "        <div class='col'>\n";
                foreach ($column['widgets'] as $widget) {
                    $html .= $this->renderWidgetBlade($widget);
                }
                $html .= "        </div>\n";
            }
            $html .= "    </div>\n";
        }
        $html .= "    <button type='submit' class='btn btn-primary'>Enviar</button>\n</form>\n";
        return $html;
    }

    // Genera HTML puro (sin Blade, sin directivas Laravel)
    private function generatePureHtml($sections)
    {
        // Encabezado HTML completo y Bootstrap CDN
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='es'>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1'>\n<title>Formulario</title>\n<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>\n</head>\n<body class='p-4'>\n";
        $html .= "<form method='post' action='#'>\n";
        foreach ($sections as $section) {
            $html .= "    <div class='row mb-4'>\n";
            foreach ($section['columns'] as $column) {
                $html .= "        <div class='col'>\n";
                foreach ($column['widgets'] as $widget) {
                    $html .= $this->renderWidgetHtml($widget);
                }
                $html .= "        </div>\n";
            }
            $html .= "    </div>\n";
        }
        $html .= "    <button type='submit' class='btn btn-primary'>Enviar</button>\n</form>\n";
        $html .= "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>\n";
        $html .= "</body>\n</html>\n";
        return $html;
    }

    // Renderiza cada widget (simplificado; puedes expandir para soportar todas las props)
    private function renderWidgetBlade($widget, $blade = true)
    {
        if (isset($widget['hidden']) && $widget['hidden']) return '';
        $label = isset($widget['label']) ? $widget['label'] : '';
        $name = isset($widget['name']) ? $widget['name'] : '';
        $required = (isset($widget['required']) && $widget['required']) ? 'required' : '';
        $disabled = (isset($widget['disabled']) && $widget['disabled']) ? 'disabled' : '';
        $readonly = (isset($widget['readonly']) && $widget['readonly']) ? 'readonly' : '';
        $placeholder = isset($widget['placeholder']) ? $widget['placeholder'] : '';
        $maxlength = isset($widget['maxlength']) ? $widget['maxlength'] : '';
        $minlength = isset($widget['minlength']) ? $widget['minlength'] : '';
        $min = isset($widget['min']) ? $widget['min'] : '';
        $max = isset($widget['max']) ? $widget['max'] : '';
        $step = isset($widget['step']) ? $widget['step'] : '';
        $pattern = isset($widget['pattern']) ? $widget['pattern'] : '';
        $multiple = (isset($widget['multiple']) && $widget['multiple']) ? 'multiple' : '';
        $size = isset($widget['size']) ? $widget['size'] : '';
        $accept = isset($widget['accept']) ? $widget['accept'] : '';
        $value = isset($widget['value']) ? $widget['value'] : '';
        $inline = (isset($widget['inline']) && $widget['inline']);
        $checked = (isset($widget['checked']) && $widget['checked']) ? 'checked' : '';
        $default = isset($widget['default']) ? $widget['default'] : '';
        $options = isset($widget['options']) ? $widget['options'] : [];
        $id = isset($widget['id']) ? $widget['id'] : '';
        $html = "";
        if ($label && !in_array($widget['type'], ['checkbox', 'switch', 'radio', 'button', 'static'])) {
            $html .= "<label class='form-label fw-semibold mb-1'>{$label}</label>\n";
        }
        switch ($widget['type']) {
            case 'text':
                $html .= "<input type='text' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' maxlength='{$maxlength}' pattern='{$pattern}' {$readonly} {$disabled} {$required}/>\n";
                break;
            case 'textarea':
                $rows = isset($widget['rows']) ? $widget['rows'] : 3;
                $html .= "<textarea class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' rows='{$rows}' maxlength='{$maxlength}' {$readonly} {$disabled} {$required}></textarea>\n";
                break;
            case 'email':
                $html .= "<input type='email' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' maxlength='{$maxlength}' pattern='{$pattern}' {$disabled} {$required}/>\n";
                break;
            case 'password':
                $html .= "<input type='password' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' minlength='{$minlength}' maxlength='{$maxlength}' {$disabled} {$required}/>\n";
                break;
            case 'number':
                $html .= "<input type='number' class='form-control' name='{$name}' id='{$id}' min='{$min}' max='{$max}' step='{$step}' {$disabled} {$required}/>\n";
                break;
            case 'date':
                $html .= "<input type='date' class='form-control' name='{$name}' id='{$id}' min='{$min}' max='{$max}' {$disabled} {$required}/>\n";
                break;
            case 'file':
                $html .= "<input type='file' class='form-control' name='{$name}' id='{$id}' accept='{$accept}' {$multiple} {$disabled} {$required}/>\n";
                break;
            case 'color':
                $html .= "<input type='color' class='form-control form-control-color' name='{$name}' id='{$id}' value='{$value}' {$disabled}/>\n";
                break;
            case 'range':
                $html .= "<input type='range' class='form-range' name='{$name}' id='{$id}' min='{$min}' max='{$max}' step='{$step}' value='{$value}' {$disabled} {$required}/>\n";
                break;
            case 'select':
                $html .= "<select class='form-select' name='{$name}' id='{$id}' {$multiple} size='{$size}' {$disabled} {$required}>\n";
                foreach ($options as $opt) {
                    $html .= "    <option>{$opt}</option>\n";
                }
                $html .= "</select>\n";
                break;
            case 'checkbox':
                $inlineClass = $inline ? 'form-check-inline' : '';
                $html .= "<div class='form-check {$inlineClass}'><input class='form-check-input' type='checkbox' name='{$name}' id='{$id}' {$checked} {$disabled} {$required}><label class='form-check-label ms-2'>{$label}</label></div>\n";
                break;
            case 'switch':
                $inlineClass = $inline ? 'form-check-inline' : '';
                $html .= "<div class='form-check form-switch {$inlineClass}'><input class='form-check-input' type='checkbox' role='switch' name='{$name}' id='{$id}' {$checked} {$disabled} {$required}><label class='form-check-label ms-2'>{$label}</label></div>\n";
                break;
            case 'radio':
                foreach ($options as $opt) {
                    $isDefault = ($default == $opt) ? 'checked' : '';
                    $inlineClass = $inline ? 'form-check-inline' : '';
                    $html .= "<div class='form-check {$inlineClass}'><input class='form-check-input' type='radio' name='{$name}' id='{$id}' value='{$opt}' {$isDefault} {$disabled} {$required}><label class='form-check-label ms-2'>{$opt}</label></div>\n";
                }
                break;
            case 'button':
                $html .= "<button class='btn btn-primary w-100' id='{$id}' type='button' {$disabled}>{$label}</button>\n";
                break;
            case 'static':
                $html .= "<div class='form-text text-muted' id='{$id}'>{$label}</div>\n";
                break;
            default:
                $html .= "<input type='text' class='form-control' name='{$name}' id='{$id}' {$disabled}/>\n";
        }
        return $html;
    }

    // Renderiza cada widget para HTML plano
    private function renderWidgetHtml($widget)
    {
        if (isset($widget['hidden']) && $widget['hidden']) return '';
        $label = isset($widget['label']) ? $widget['label'] : '';
        $name = isset($widget['name']) ? $widget['name'] : '';
        $id = isset($widget['id']) ? $widget['id'] : '';
        $required = (isset($widget['required']) && $widget['required']) ? 'required' : '';
        $disabled = (isset($widget['disabled']) && $widget['disabled']) ? 'disabled' : '';
        $readonly = (isset($widget['readonly']) && $widget['readonly']) ? 'readonly' : '';
        $placeholder = isset($widget['placeholder']) ? $widget['placeholder'] : '';
        $maxlength = isset($widget['maxlength']) ? $widget['maxlength'] : '';
        $minlength = isset($widget['minlength']) ? $widget['minlength'] : '';
        $min = isset($widget['min']) ? $widget['min'] : '';
        $max = isset($widget['max']) ? $widget['max'] : '';
        $step = isset($widget['step']) ? $widget['step'] : '';
        $pattern = isset($widget['pattern']) ? $widget['pattern'] : '';
        $multiple = (isset($widget['multiple']) && $widget['multiple']) ? 'multiple' : '';
        $size = isset($widget['size']) && $widget['size'] !== null ? $widget['size'] : 1; // Asegurar que $size siempre tenga valor por defecto si no está definido
        $accept = isset($widget['accept']) ? $widget['accept'] : '';
        $value = isset($widget['value']) ? $widget['value'] : '';
        $inline = (isset($widget['inline']) && $widget['inline']);
        $checked = (isset($widget['checked']) && $widget['checked']) ? 'checked' : '';
        $default = isset($widget['default']) ? $widget['default'] : '';
        $options = isset($widget['options']) ? $widget['options'] : [];
        $html = "";
        if ($label && !in_array($widget['type'], ['checkbox', 'switch', 'radio', 'button', 'static'])) {
            $html .= "<label class='form-label fw-semibold mb-1'>{$label}</label>\n";
        }
        switch ($widget['type']) {
            case 'text':
                $html .= "<input type='text' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' maxlength='{$maxlength}' pattern='{$pattern}' {$readonly} {$disabled} {$required}/><br>\n";
                break;
            case 'textarea':
                $rows = isset($widget['rows']) ? $widget['rows'] : 3;
                $html .= "<textarea class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' rows='{$rows}' maxlength='{$maxlength}' {$readonly} {$disabled} {$required}></textarea><br>\n";
                break;
            case 'email':
                $html .= "<input type='email' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' maxlength='{$maxlength}' pattern='{$pattern}' {$disabled} {$required}/><br>\n";
                break;
            case 'password':
                $html .= "<input type='password' class='form-control' name='{$name}' id='{$id}' placeholder='{$placeholder}' minlength='{$minlength}' maxlength='{$maxlength}' {$disabled} {$required}/><br>\n";
                break;
            case 'number':
                $html .= "<input type='number' class='form-control' name='{$name}' id='{$id}' min='{$min}' max='{$max}' step='{$step}' {$disabled} {$required}/><br>\n";
                break;
            case 'date':
                $html .= "<input type='date' class='form-control' name='{$name}' id='{$id}' min='{$min}' max='{$max}' {$disabled} {$required}/><br>\n";
                break;
            case 'file':
                $html .= "<input type='file' class='form-control' name='{$name}' id='{$id}' accept='{$accept}' {$multiple} {$disabled} {$required}/><br>\n";
                break;
            case 'color':
                $html .= "<input type='color' class='form-control form-control-color' name='{$name}' id='{$id}' value='{$value}' {$disabled}/><br>\n";
                break;
            case 'range':
                $html .= "<input type='range' class='form-range' name='{$name}' id='{$id}' min='{$min}' max='{$max}' step='{$step}' value='{$value}' {$disabled} {$required}/><br>\n";
                break;
            case 'select':
                $html .= "<select class='form-select' name='{$name}' id='{$id}' {$multiple} size='{$size}' {$disabled} {$required}>\n";
                foreach ($options as $opt) {
                    $html .= "    <option>{$opt}</option>\n";
                }
                $html .= "</select><br>\n";
                break;
            case 'checkbox':
                $inlineClass = $inline ? 'form-check-inline' : '';
                $html .= "<div class='form-check {$inlineClass}'><input class='form-check-input' type='checkbox' name='{$name}' id='{$id}' {$checked} {$disabled} {$required}><label class='form-check-label ms-2'>{$label}</label></div><br>\n";
                break;
            case 'switch':
                $inlineClass = $inline ? 'form-check-inline' : '';
                $html .= "<div class='form-check form-switch {$inlineClass}'><input class='form-check-input' type='checkbox' role='switch' name='{$name}' id='{$id}' {$checked} {$disabled} {$required}><label class='form-check-label ms-2'>{$label}</label></div><br>\n";
                break;
            case 'radio':
                foreach ($options as $opt) {
                    $isDefault = ($default == $opt) ? 'checked' : '';
                    $inlineClass = $inline ? 'form-check-inline' : '';
                    $html .= "<div class='form-check {$inlineClass}'><input class='form-check-input' type='radio' name='{$name}' id='{$id}' value='{$opt}' {$isDefault} {$disabled} {$required}><label class='form-check-label ms-2'>{$opt}</label></div><br>\n";
                }
                break;
            case 'button':
                $html .= "<button class='btn btn-primary w-100' id='{$id}' type='button' {$disabled}>{$label}</button><br>\n";
                break;
            case 'static':
                $html .= "<div class='form-text text-muted' id='{$id}'>{$label}</div><br>\n";
                break;
            default:
                $html .= "<input type='text' class='form-control' name='{$name}' id='{$id}' {$disabled}/><br>\n";
        }
        return $html;
    }
}
