<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LocalAuthController;
use App\Livewire\Admin\AsignaturasIndex;
use App\Livewire\Admin\EquiposIndex;
use App\Livewire\Admin\ProgramasIndex;
use App\Livewire\Admin\SedesIndex;
use App\Livewire\Admin\SolicitudesIndex;
use App\Livewire\DisponibilidadCalendar;
use App\Livewire\MisSolicitudes;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [LocalAuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.attempt');

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->middleware('guest')
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->middleware('guest')
    ->name('auth.google.callback');

Route::post('/logout', [GoogleAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::livewire('/', DisponibilidadCalendar::class)->name('disponibilidad');
    Route::livewire('/mis-solicitudes', MisSolicitudes::class)->name('solicitudes.mine');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/sedes', SedesIndex::class)->name('sedes');
    Route::livewire('/programas', ProgramasIndex::class)->name('programas');
    Route::livewire('/asignaturas', AsignaturasIndex::class)->name('asignaturas');
    Route::livewire('/equipos', EquiposIndex::class)->name('equipos');
    Route::livewire('/solicitudes', SolicitudesIndex::class)->name('solicitudes');
});
