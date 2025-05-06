@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Detalle de registro en {{ $formModel->name }}</h2>
    <table class="table table-bordered">
        <tbody>
            <tr><th>ID</th><td>{{ $record->id }}</td></tr>
            @foreach($fields as $field)
                <tr>
                    <th>{{ $field->label }}</th>
                    <td>{{ $record->{$field->name} }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('dynamic-crud.index', $form) }}" class="btn btn-secondary">Volver</a>
    <a href="{{ route('dynamic-crud.edit', [$form, $record->id]) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
