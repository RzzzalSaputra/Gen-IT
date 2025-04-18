<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Configure SweetAlert defaults to match dark theme
    Swal.mixin({
        background: '#1f2937',
        color: '#f3f4f6',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280'
    });
</script>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col">
        <div class="flex-grow bg-black">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
            
        @include('layouts.footer')
    </body>
</html>