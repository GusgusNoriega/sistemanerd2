@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')
<div class="p-4">
        <input type="text" placeholder="Input 1" class="border border-gray-300 p-2 mb-2 w-full">
        <button class="popup-button bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Abrir Pop-up
        </button>
    </div>

    <div class="p-4">
        <input type="text" placeholder="Input 2" class="border border-gray-300 p-2 mb-2 w-full">
        <button class="popup-button bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Abrir Pop-up
        </button>
    </div>

    <!-- Pop-up -->
    <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-30 hidden">
        <div class="bg-white rounded-lg shadow-lg w-[90%] h-[80%] md:w-[90%] md:h-[90%] p-6 relative">
            <button id="close-popup" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold focus:outline-none">
                &times;
            </button>
            <x-image-gallery></x-image-gallery>
            <p class="mt-4">Este es el contenido del pop-up. 46465454654656456465464</p>
        </div>
    </div>
    

    <!-- JavaScript -->
    <script>
        // Obtener el elemento del pop-up y el botón de cierre
        const popup = document.getElementById('popup');
        const closeBtn = document.getElementById('close-popup');

        // Función para abrir el pop-up
        function openPopup() {
            popup.classList.remove('hidden');
        }

        // Añadir evento a todos los botones con la clase 'popup-button'
        const buttons = document.querySelectorAll('.popup-button');
        buttons.forEach(button => {
            button.addEventListener('click', openPopup);
        });

        // Función para cerrar el pop-up
        function closePopup() {
            popup.classList.add('hidden');
        }

        // Evento para cerrar el pop-up al hacer clic en la 'x'
        closeBtn.addEventListener('click', closePopup);

     
    </script>
@endsection

@stack('scripts')

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection