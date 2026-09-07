<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Préstamo de Videobeams</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
        @auth
            <nav class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-6 min-w-0">
                        <a href="{{ route('disponibilidad') }}" class="flex items-center gap-2 shrink-0">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white text-sm font-semibold">VB</span>
                            <span class="font-semibold text-gray-900 hidden sm:inline">Préstamo de Videobeams</span>
                        </a>
                        <div class="hidden md:flex items-center gap-1 text-sm">
                            <a href="{{ route('disponibilidad') }}"
                               class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('disponibilidad') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Disponibilidad
                            </a>
                            <a href="{{ route('solicitudes.mine') }}"
                               class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('solicitudes.mine') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Mis solicitudes
                            </a>
                            @if (auth()->user()->is_admin)
                                <span class="w-px h-5 bg-gray-200 mx-1"></span>
                                <a href="{{ route('admin.solicitudes') }}"
                                   class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('admin.solicitudes') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Solicitudes
                                </a>
                                <a href="{{ route('admin.sedes') }}"
                                   class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('admin.sedes') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Sedes
                                </a>
                                <a href="{{ route('admin.programas') }}"
                                   class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('admin.programas') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Programas
                                </a>
                                <a href="{{ route('admin.asignaturas') }}"
                                   class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('admin.asignaturas') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Asignaturas
                                </a>
                                <a href="{{ route('admin.equipos') }}"
                                   class="px-3 py-2 rounded-lg font-medium transition-colors duration-150 {{ request()->routeIs('admin.equipos') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Equipos
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-200 text-xs font-semibold text-gray-600">
                                {{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}
                            </span>
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-150 active:scale-[0.97]">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        @endauth

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 animate-fade-in-up">
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
