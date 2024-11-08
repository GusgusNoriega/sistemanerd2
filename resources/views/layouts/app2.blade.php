<!DOCTYPE html>
<html lang="en">
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
<body class="bg-gray-100 text-gray-900">

    <!-- Main Layout Wrapper -->
    <div class="min-h-screen flex flex-col">
        
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4">
            @yield('header') <!-- Aquí se pueden incluir componentes -->
        </header>
        
        <!-- Main Content Wrapper -->
        <div class="flex flex-1">
            
            <!-- Sidebar -->
            
                @yield('sidebar') <!-- Aquí se pueden incluir componentes -->
        
            
            <!-- Main Content Area -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
            
        </div>

        <!-- Footer -->
        <footer class="bg-gray-700 text-white p-4">
            @yield('footer') <!-- Aquí se pueden incluir componentes -->
        </footer>
    </div>

</body>
</html>