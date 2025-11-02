

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home Quest</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/verification-modal.css') }}" rel="stylesheet" />
    @vite(['resources/js/app.js', 'resources/js/verification.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div id="app">
        @include('admin.sidebar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
