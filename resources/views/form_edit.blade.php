@extends('layouts.builder')

@section('title', 'Editar Formulario')

@section('content')
<style>
    body { background: #f8f9fa !important; }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 rounded-top-4 pb-0">
                    <h3 class="fw-bold text-primary mb-1"><i class="bi bi-pencil-square me-2"></i>Editar Formulario</h3>
                    <hr class="mt-2 mb-0" style="border-color:#0d6efd;opacity:.2;">
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('forms.update', $form->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Nombre del formulario</label>
                            <input type="text" name="name" value="{{ old('name', $form->name) }}" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('forms.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-save me-1"></i>Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
