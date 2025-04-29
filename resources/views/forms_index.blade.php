@extends('layouts.builder')

@section('title', 'Formularios creados')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background:transparent;">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 pb-0 d-flex align-items-center">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-ui-checks-grid me-2"></i>Formularios creados</h3>
                <hr class="mt-2 mb-0 w-100" style="border-color:#0d6efd;opacity:.2;">
            </div>
            <div class="card-body bg-white">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-4">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Formulario</th>
                                <th>Tabla</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($forms as $form)
                                <tr>
                                    <td>{{ $form->id }}</td>
                                    <td>{{ $form->name }}</td>
                                    <td>{{ $form->table_name }}</td>
                                    <td>
                                        <a href="{{ route('forms.show', $form->id) }}" class="btn btn-outline-primary btn-sm me-1"><i class="bi bi-eye"></i> Ver registros</a>
                                        <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil-square"></i> Editar</a>
                                        <form action="{{ route('forms.destroy', $form->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este formulario?')"><i class="bi bi-trash"></i> Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-secondary">No hay formularios creados aún.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('table.setup') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Nuevo Formulario</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
