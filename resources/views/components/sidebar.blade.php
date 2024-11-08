<!-- resources/views/components/sidebar.blade.php -->
<div x-data="{ open: false }" class="relative">
    <!-- Botón para abrir/cerrar la barra lateral -->
    <button @click="open = !open" class="p-2 bg-blue-500">
        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Ícono de hamburguesa -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Contenido de la barra lateral -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 w-64 h-full bg-gray-800 text-white shadow-lg"
    >
        <!-- Aquí va el contenido de tu barra lateral -->
        <p class="p-4">Contenido de la barra lateral</p>
    </div>
</div>