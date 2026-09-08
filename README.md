# Préstamo de Videobeams — Unipaz

Aplicación web para gestionar el préstamo de videobeams (proyectores) de la Escuela de Ciencia Social de la Universidad Unipaz. Reemplaza el formulario manual de Google Apps Script por un sistema con validación de disponibilidad en tiempo real y confirmación automática.

## Características

- **Autenticación**: inicio de sesión con Google (restringido al dominio institucional) o con usuario/contraseña local.
- **Solicitudes auto-confirmadas**: al registrar una solicitud con disponibilidad, queda confirmada de inmediato (sin aprobación manual).
- **Calendario de disponibilidad**: vista interactiva (FullCalendar) con los equipos reservados por fecha y hora.
- **Mis solicitudes**: cada docente gestiona sus propias solicitudes (editar/cancelar mientras la fecha no haya pasado).
- **Panel de administración**: gestión de sedes, programas, asignaturas, equipos y de todas las solicitudes del sistema.
- **Validación de solapamiento**: evita reservar el mismo equipo en horarios cruzados; asignación automática de equipo cuando no se especifica uno.

## Stack técnico

- Laravel 13 (PHP 8.5)
- Livewire 4
- Laravel Socialite (Google OAuth)
- MySQL
- FullCalendar + Alpine.js
- Tailwind CSS v4
- Pest (testing)

## Instalación

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configura en `.env`:

```
DB_CONNECTION=mysql
DB_DATABASE=prestamos_unipaz
DB_USERNAME=root
DB_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

INSTITUTIONAL_EMAIL_DOMAIN=unipaz.edu.co
APP_LOCALE=es
```

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

## Tests

```bash
php artisan test --compact
```
