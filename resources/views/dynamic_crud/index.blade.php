@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Registros de {{ $formModel->name }}</h2>
    <a href="{{ route('dynamic-crud.create', $form) }}" class="btn btn-primary mb-3">Crear nuevo</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                @foreach($fields as $field)
                    <th>{{ $field->label }}</th>
                @endforeach
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    @foreach($fields as $field)
                        <td>{{ $record->{$field->name} }}</td>
                    @endforeach
                    <td>
                        <a href="{{ route('dynamic-crud.show', [$form, $record->id]) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('dynamic-crud.edit', [$form, $record->id]) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('dynamic-crud.destroy', [$form, $record->id]) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $records->links() }}
</div>
@endsection
