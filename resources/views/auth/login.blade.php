<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Iniciar sesión</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <div class="flex flex-col items-center text-center mb-6">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600 text-white text-lg font-semibold mb-4">VB</span>
                <h1 class="text-xl font-semibold text-gray-900">Préstamo de Videobeams</h1>
                <p class="text-sm text-gray-500 mt-1">Inicia sesión con tu correo institucional para solicitar un equipo.</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                @if (session('error'))
                    <div class="bg-red-50 text-red-700 text-sm rounded-lg px-3 py-2.5 mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if (config('services.google.client_id'))
                    <a href="{{ route('auth.google.redirect') }}"
                       class="inline-flex items-center justify-center gap-2 w-full bg-brand-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-brand-700 active:scale-[0.97] transition-[transform,background-color] duration-150 ease-out">
                        Iniciar sesión con Google
                    </a>

                    <div class="flex items-center gap-3 my-5">
                        <div class="flex-1 border-t border-gray-200"></div>
                        <span class="text-xs text-gray-400">o</span>
                        <div class="flex-1 border-t border-gray-200"></div>
                    </div>
                @else
                    <div class="bg-amber-50 text-amber-800 text-xs rounded-lg px-3 py-2.5 mb-5 text-left">
                        El inicio de sesión con Google aún no está configurado. Usa el acceso local mientras tanto.
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="text-left space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Contraseña</label>
                        <input id="password" type="password" name="password" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <button type="submit"
                            class="w-full bg-gray-900 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-gray-800 active:scale-[0.97] transition-[transform,background-color] duration-150 ease-out">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
