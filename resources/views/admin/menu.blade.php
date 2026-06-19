<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

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
                {{ Auth::user()->name }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('menu') }}">
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
    <h2 class="text-center mb-5">Menú Principal</h2>

    <div class="row g-4">

        @php
            $menus = [
                ['titulo'=>'Contactos','icono'=>'👥','ruta'=>'contacto.dashboard'],
                ['titulo'=>'Compras','icono'=>'🛒','ruta'=>'contacto.empresa.index'],
                ['titulo'=>'Inventario','icono'=>'📦','ruta'=>'contacto.empresa.index'],
                ['titulo'=>'Ventas','icono'=>'💰','ruta'=>'contacto.empresa.index'],
                ['titulo'=>'CRM','icono'=>'📊','ruta'=>'admin'],
                ['titulo'=>'Cocina','icono'=>'🍽️','ruta'=>'admin'],
                ['titulo'=>'Configuración','icono'=>'⚙️','ruta'=>'contacto.empresa.index'],
            ];
        @endphp

        @foreach($menus as $menu)
            <div class="col-md-3 col-sm-6">
                <a href="{{ route($menu['ruta']) }}" class="menu-link">
                    <div class="card menu-card shadow text-center p-4">
                        <div class="menu-icon mb-3">
                            {{ $menu['icono'] }}
                        </div>
                        <h5>{{ $menu['titulo'] }}</h5>
                    </div>
                </a>
            </div>
        @endforeach

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>