<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anatomía MEDAC — Panel y Expedientes</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Estilos y Scripts con Vite -->
    @vite(['resources/css/principal.css', 'resources/css/dashboard.css', 'resources/js/principal.js'])

    <style>
        /* Overrides para integrar Bootstrap con principal.css */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        main {
            min-height: 80vh;
        }
        .main-header, .main-footer {
            /* Asegurar que header y footer de principal.css no choquen con bootstrap */
            position: relative;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <x-header />

    <main>
        @yield('content')
    </main>

    <x-footer />
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
