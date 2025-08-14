<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SEGAGRO - Sistema Administrativo')</title>
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/segagro.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <script src="{{ asset('js/segagro.js') }}"></script>
    @stack('scripts')
</body>
</html>