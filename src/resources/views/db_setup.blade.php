@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Configuración de la Base de Datos MySQL</h2>
    <form method="POST" action="{{ route('db.setup') }}">
        @csrf
        <div class="mb-3">
            <label for="host" class="form-label">Host</label>
            <input type="text" class="form-control" id="host" name="host" value="{{ old('host', '127.0.0.1') }}" required>
        </div>
        <div class="mb-3">
            <label for="port" class="form-label">Puerto</label>
            <input type="number" class="form-control" id="port" name="port" value="{{ old('port', '3306') }}" required>
        </div>
        <div class="mb-3">
            <label for="database" class="form-label">Base de datos</label>
            <input type="text" class="form-control" id="database" name="database" value="{{ old('database') }}" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        @if($errors->has('db'))
            <div class="alert alert-danger">{{ $errors->first('db') }}</div>
        @endif
        <button type="submit" class="btn btn-primary">Probar conexión</button>
    </form>
</div>
@endsection
