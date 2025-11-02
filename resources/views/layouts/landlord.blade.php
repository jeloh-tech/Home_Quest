<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home Quest</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/favicon/logo.png') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen">
    <!-- Fixed Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50">
        @include('landlord.sidebar')
    </div>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen">
        <main class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen overflow-auto">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
