@extends('layouts.builder')

@section('title', 'Importar Tabla')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background:transparent;">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 pb-0 d-flex align-items-center">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-table me-2"></i>Importar Tabla</h3>
                <hr class="mt-2 mb-0 w-100" style="border-color:#0d6efd;opacity:.2;">
            </div>
            <div class="card-body bg-white">
                @if(is_array($tablesToImport) && count($tablesToImport) > 0)
                    <form method="POST" action="{{ route('import.tables.import') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="table" class="form-label fw-semibold text-secondary">Selecciona una tabla física para importar:</label>
                            <select name="table" id="table" class="form-select form-select-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm">
                                <option value="">-- Selecciona una tabla --</option>
                                @foreach($tablesToImport as $table)
                                    <option value="{{ $table }}">{{ $table }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-download me-1"></i>Importar tabla</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">No hay tablas disponibles para importar.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
