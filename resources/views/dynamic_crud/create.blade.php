@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Crear registro en {{ $formModel->name }}</h2>
    <form action="{{ route('dynamic-crud.store', $form) }}" method="POST">
        @csrf
        @foreach($fields as $field)
            <div class="mb-3">
                <label class="form-label">{{ $field->label }}</label>
                <input type="text" name="{{ $field->name }}" class="form-control" value="{{ old($field->name) }}">
            </div>
        @endforeach
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('dynamic-crud.index', $form) }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
