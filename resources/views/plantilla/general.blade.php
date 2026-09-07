<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('tituloPagina')
    </title>
    <link href="{{ asset('img/abicourier.png') }}" rel="shortcut icon" />

    <!-- hay que cambiar el haref al url quequiere que aparezca en las busquedas -->
    <link rel="canonical" href="#" />
    <!-- fontwawesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.5.1-web/css/all.min.css') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: "Poppins", "Odoo Unicode Support Noto", sans-serif;
        }
    </style>

    @yield('estilos')
</head>

<body>
    @yield('contenido')


    @yield('scripts')
</body>

</html>
