<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AbiSystem') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            background: #f4f6f9;
        }

        .profile-shell {
            max-width: 1100px;
        }

        .profile-hero {
            border: 0;
            border-radius: 20px;
            background: linear-gradient(135deg, #198754, #157347 55%, #0f5132 100%);
            color: #fff;
            box-shadow: 0 18px 40px rgba(21, 87, 36, 0.22);
        }

        .profile-hero p {
            color: rgba(255, 255, 255, 0.85);
        }

        .profile-section {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .profile-section .card-body {
            background: #fff;
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $displayName = trim(($user->persona->Nombres ?? '').' '.($user->persona->Apellidos ?? '')) ?: ($user->name ?? $user->email);
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
    <div class="container">
        <a class="navbar-brand" href="#">
            {{ config('app.name') }}
        </a>

        <div class="dropdown ms-auto">
            <button
                id="userMenuToggle"
                type="button"
                class="btn btn-light dropdown-toggle"
                aria-expanded="false"
            >
                {{ trim(($user->persona->Nombres ?? '').' '.($user->persona->Apellidos ?? '')) ?: ($user->name ?? $user->email) }}
            </button>

            <ul id="userMenu" class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('menu') }}">
                        Menu
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                        Cerrar sesión
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<div class="container py-5 profile-shell">
    <div class="card profile-hero mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h1 class="h2 mb-2">Perfil de usuario</h1>
                    <p class="mb-0">Administra tu información personal, seguridad de acceso y sesiones activas desde un solo lugar.</p>
                </div>

                @can('assign-menu-permissions')
                    <a href="{{ route('users.permissions.edit', auth()->id()) }}" class="btn btn-light fw-semibold">
                        Asignar permisos
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        <div class="card profile-section mb-4">
            <div class="card-body p-4 p-lg-5">
                @livewire('profile.update-profile-information-form')
            </div>
        </div>
    @endif

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        <div class="card profile-section mb-4">
            <div class="card-body p-4 p-lg-5">
                @livewire('profile.update-password-form')
            </div>
        </div>
    @endif

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <div class="card profile-section mb-4">
            <div class="card-body p-4 p-lg-5">
                @livewire('profile.two-factor-authentication-form')
            </div>
        </div>
    @endif

    <div class="card profile-section mb-4">
        <div class="card-body p-4 p-lg-5">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>
    </div>

    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        <div class="card profile-section">
            <div class="card-body p-4 p-lg-5">
                @livewire('profile.delete-user-form')
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('userMenuToggle');
        const menu = document.getElementById('userMenu');

        if (!toggle || !menu) {
            return;
        }

        const closeMenu = () => {
            menu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const isOpen = menu.classList.toggle('show');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (menu.contains(event.target) || toggle.contains(event.target)) {
                return;
            }

            closeMenu();
        });
    });
</script>
@livewireScripts
</body>
</html>
