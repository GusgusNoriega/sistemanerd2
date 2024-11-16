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
        <header>
            @yield('header') <!-- Aquí se pueden incluir componentes -->
        </header>
        
        <!-- Main Content Wrapper -->
        <div class="flex overflow-hidden bg-white pt-16">
            
            <!-- Sidebar -->
            
                @yield('sidebar') <!-- Aquí se pueden incluir componentes -->
        
            
            <!-- Main Content Area -->
            <div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
                <main class="p-6">
                    @yield('content')
                </main>

                <!-- Footer -->
                    <footer class="bg-white md:flex md:items-center md:justify-between shadow rounded-lg p-4 md:p-6 xl:p-8 my-6 mx-4">
                        @yield('footer') <!-- Aquí se pueden incluir componentes -->
                    </footer>
                    <p class="text-center text-sm text-gray-500 my-10">
                        &copy; 2019-2021 <a href="#" class="hover:underline" target="_blank">Themesberg</a>. All rights reserved.
                    </p>
            </div>
        </div>

      
    </div>

</body>
</html>