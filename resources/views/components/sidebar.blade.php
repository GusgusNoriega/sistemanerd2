<!-- resources/views/components/sidebar.blade.php -->
<div x-data="{
        open: window.innerWidth >= 768,
        toggle() { this.open = !this.open },
        checkScreen() {
            if (window.innerWidth >= 768) {
                this.open = true;
            } else {
                this.open = false;
            }
        }
    }"
    x-init="checkScreen()"
    @resize.window="checkScreen()"
    class="relative h-full"
>
    <!-- Contenido de la barra lateral -->
    <div
        x-show="open"
        x-cloak
        :class="{'fixed inset-y-0 left-0': window.innerWidth < 768, 'relative h-full': window.innerWidth >= 768}"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="w-64 bg-gray-800 text-white shadow-lg"
    >
        <!-- Aquí va el contenido de tu barra lateral -->
        <p class="p-4">Contenido de la barra lateral</p>
    </div>

    <!-- Botón para abrir/cerrar la barra lateral (solo en móviles) -->
    <button @click="toggle()" class="p-2 bg-blue-500 text-white fixed bottom-0 left-0 w-full md:hidden">
        <svg class="w-6 h-6 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Ícono de hamburguesa -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>