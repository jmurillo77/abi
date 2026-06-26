<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .menu-card {
            transition: 0.3s;
            border: none;
            border-radius: 15px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,.15);
        }

        .menu-icon {
            font-size: 50px;
        }

        .menu-icon i {
            color: #198754;
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
    <div class="container">
        <a class="navbar-brand" href="#">
            {{ config('app.name') }}
        </a>

        <div class="dropdown ms-auto">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                {{ trim(($user->persona->Nombres ?? '').' '.($user->persona->Apellidos ?? '')) ?: ($user->name ?? $user->email) }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        Perfil
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

<div class="container py-5">
    <h2 class="text-center mb-4">Menú Principal</h2>

    @if($menus->isEmpty())
        <div class="alert alert-warning text-center mb-0">
            No hay menús asignados al rol de este usuario.
        </div>
    @else
        <div class="row g-4">
            @foreach($menus as $menu)
                @php($menuLink = str_contains($menu->link, '?') ? $menu->link.'&menu_id='.$menu->IdMenu : $menu->link.'?menu_id='.$menu->IdMenu)
                <div class="col-md-3 col-sm-6">
                    <a href="{{ $menuLink }}" class="menu-link">
                        <div class="card menu-card shadow text-center p-4 h-100">
                            <div class="menu-icon mb-3">
                                @php($menuIconRaw = trim((string) ($menu->Icono ?? '')))
                                @php($menuIconParts = array_map('trim', explode('|', $menuIconRaw, 2)))
                                @php($menuIconClass = $menuIconParts[0] ?? '')
                                @php($menuIconColor = $menuIconParts[1] ?? '')
                                @if($menuIconRaw !== '')
                                    @if(str_contains($menuIconRaw, '<i'))
                                        {!! $menuIconRaw !!}
                                    @else
                                        <i class="{{ $menuIconClass }}" @if($menuIconColor !== '') style="color: {{ $menuIconColor }};" @endif></i>
                                    @endif
                                @else
                                    <i class="fas fa-folder"></i>
                                @endif
                            </div>
                            <h5>{{ $menu->Titulo }}</h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>