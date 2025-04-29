@extends('layouts.builder')

@section('title', 'Configurar Base de Datos')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background:transparent;">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 pb-0 d-flex align-items-center">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-database-gear me-2"></i>Configurar Base de Datos</h3>
                <hr class="mt-2 mb-0 w-100" style="border-color:#0d6efd;opacity:.2;">
            </div>
            <div class="card-body bg-white">
                <form method="POST" action="{{ route('db.setup.test') }}" id="db-setup-form">
                    @csrf
                    <div class="mb-4">
                        <label for="host" class="form-label fw-semibold text-secondary">Host</label>
                        <input id="host" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="text" name="host" value="{{ old('host', '127.0.0.1') }}" required placeholder="127.0.0.1" />
                        @error('host')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="port" class="form-label fw-semibold text-secondary">Puerto</label>
                        <input id="port" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="number" name="port" value="{{ old('port', '3306') }}" required placeholder="3306" />
                        @error('port')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="database" class="form-label fw-semibold text-secondary">Base de datos</label>
                        <input id="database" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="text" name="database" value="{{ old('database') }}" required placeholder="Nombre de la BD" />
                        @error('database')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="username" class="form-label fw-semibold text-secondary">Usuario</label>
                        <input id="username" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="text" name="username" value="{{ old('username') }}" required placeholder="root o tu usuario" />
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-secondary">Contraseña</label>
                        <input id="password" class="form-control form-control-lg bg-light border-2 border-primary-subtle rounded-3 shadow-sm" type="password" name="password" placeholder="(opcional)" />
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-save me-1"></i>Guardar configuración</button>
                    </div>
                </form>
                <div class="mt-4">
                    @if(session('success'))
                        <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#16a34a; color:#fff;">{{ session('success') }}</div>
                    @endif
                    @if($errors->has('db'))
                        <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#dc2626; color:#fff;">{{ $errors->first('db') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
