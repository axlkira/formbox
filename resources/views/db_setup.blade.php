<x-guest-layout>
    <div class="mb-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24"><path fill="#6366f1" d="M19.5 7.5v7.379a2.25 2.25 0 0 1-1.06 1.91l-5.25 3.281a2.25 2.25 0 0 1-2.38 0l-5.25-3.28A2.25 2.25 0 0 1 4.5 14.88V7.5l6.75-4.219a2.25 2.25 0 0 1 2.5 0L19.5 7.5Z"/><path fill="#181f2a" d="m5.25 8.058 6.75 4.219 6.75-4.219"/></svg>
    </div>
    <form method="POST" action="{{ route('db.setup.test') }}">
        @csrf
        <!-- Host -->
        <div>
            <x-input-label for="host" :value="'Host'" />
            <x-text-input id="host" class="block mt-1 w-full" type="text" name="host" value="{{ old('host', '127.0.0.1') }}" required autofocus placeholder="127.0.0.1" />
            <x-input-error :messages="$errors->get('host')" class="mt-2" />
        </div>
        <!-- Puerto -->
        <div class="mt-4">
            <x-input-label for="port" :value="'Puerto'" />
            <x-text-input id="port" class="block mt-1 w-full" type="number" name="port" value="{{ old('port', '3306') }}" required placeholder="3306" />
            <x-input-error :messages="$errors->get('port')" class="mt-2" />
        </div>
        <!-- Base de datos -->
        <div class="mt-4">
            <x-input-label for="database" :value="'Base de datos'" />
            <x-text-input id="database" class="block mt-1 w-full" type="text" name="database" value="{{ old('database') }}" required placeholder="Nombre de la BD" />
            <x-input-error :messages="$errors->get('database')" class="mt-2" />
        </div>
        <!-- Usuario -->
        <div class="mt-4">
            <x-input-label for="username" :value="'Usuario'" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ old('username') }}" required placeholder="root o tu usuario" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Contraseña'" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="(opcional)" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Mensajes de éxito/error -->
        <div class="mt-4">
            @if(session('success'))
                <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#16a34a; color:#fff;">{{ session('success') }}</div>
            @endif
            @if($errors->has('db'))
                <div class="rounded-md p-3 mb-2 text-sm font-semibold" style="background:#dc2626; color:#fff;">{{ $errors->first('db') }}</div>
            @endif
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3 w-full justify-center">
                {{ 'Probar conexión' }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<!-- Animate.css CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
