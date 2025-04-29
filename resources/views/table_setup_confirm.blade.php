<x-guest-layout>
    <div class="mb-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24"><path fill="#6366f1" d="M19.5 7.5v7.379a2.25 2.25 0 0 1-1.06 1.91l-5.25 3.281a2.25 2.25 0 0 1-2.38 0l-5.25-3.28A2.25 2.25 0 0 1 4.5 14.88V7.5l6.75-4.219a2.25 2.25 0 0 1 2.5 0L19.5 7.5Z"/><path fill="#181f2a" d="m5.25 8.058 6.75 4.219 6.75-4.219"/></svg>
    </div>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-100">Confirmar creación de tabla</h2>
        <p class="text-gray-400">Por favor revisa los datos antes de crear la tabla en la base de datos.</p>
    </div>
    <div class="mb-4">
        <div class="font-semibold text-gray-200">Nombre de la tabla:</div>
        <div class="mb-2 text-lg text-indigo-400">{{ $setup['table_name'] }}</div>
        <div class="font-semibold text-gray-200 mt-4">Campos:</div>
        <table class="w-full text-sm text-gray-200 mt-2">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="py-1">Nombre</th>
                    <th class="py-1">Tipo</th>
                    <th class="py-1">Requerido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($setup['fields'] as $field)
                    <tr class="border-b border-gray-800">
                        <td class="py-1">{{ $field['name'] }}</td>
                        <td class="py-1">{{ ucfirst($field['type']) }}</td>
                        <td class="py-1">{{ isset($field['required']) ? 'Sí' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(session('success'))
        <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#16a34a; color:#fff;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#dc2626; color:#fff;">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('table.setup.create') }}">
        @csrf
        <div class="flex items-center justify-between mt-6 gap-2">
            <a href="{{ route('table.setup') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-200 text-xs">Volver</a>
            <x-primary-button class="ms-3 w-full justify-center">
                {{ 'Crear tabla en base de datos' }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
