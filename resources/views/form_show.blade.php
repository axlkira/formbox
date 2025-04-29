@extends('layouts.builder')

@section('title', 'Detalles del Formulario')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background:transparent;">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 pb-0 d-flex align-items-center">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-ui-checks-grid me-2"></i>Detalles del Formulario</h3>
                <hr class="mt-2 mb-0 w-100" style="border-color:#0d6efd;opacity:.2;">
            </div>
            <div class="card-body bg-white">
                <div class="mb-4">
                    <h4 class="mb-1 text-dark">{{ $form->name }}</h4>
                    <span class="badge bg-secondary">Tabla asociada: {{ $form->table_name }}</span>
                </div>
                <h5 class="mb-3 text-primary">Campos del Formulario</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-4">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Etiqueta</th>
                                <th>Tipo</th>
                                <th>Requerido</th>
                                <th>Orden</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fields as $field)
                                <tr>
                                    <td>{{ $field->name }}</td>
                                    <td>{{ $field->label }}</td>
                                    <td>{{ $field->type }}</td>
                                    <td>{{ $field->required ? 'Sí' : 'No' }}</td>
                                    <td>{{ $field->order }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary">No hay campos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('forms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a la lista</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
