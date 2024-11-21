@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')

<h1 class="text-3xl font-bold mb-6">Gestión de Imágenes</h1>

<!-- Formulario para agregar una nueva imagen -->
<div class="bg-white p-6 rounded shadow mb-8">
    <h2 class="text-2xl font-semibold mb-4">Agregar Nueva Imagen</h2>
    <form id="uploadForm" class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium">Nombre:</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block text-gray-700 font-medium">Imagen:</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Subir Imagen</button>
    </form>
</div>

<!-- Lista de imágenes -->
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Lista de Imágenes</h2>
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Nombre</th>
                <th class="p-2 text-left">Imagen</th>
                <th class="p-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody id="imageList" class="divide-y divide-gray-200">
            <!-- Las imágenes se cargarán aquí -->
        </tbody>
    </table>
</div>

<!-- Modal para editar imagen -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-30 hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Editar Imagen</h2>
        <form id="editForm" class="space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="block text-gray-700 font-medium">Nombre:</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Imagen (opcional):</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded p-2">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts para manejar las acciones -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const apiUrl = '/api/images';

        // Función para cargar las imágenes
        const loadImages = () => {
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const imageList = document.getElementById('imageList');
                    imageList.innerHTML = '';
                    data.data.forEach(image => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="p-2">${image.id}</td>
                            <td class="p-2">${image.name}</td>
                            <td class="p-2">
                                <img src="/storage/${image.path}" alt="${image.name}" class="w-16 h-16 object-cover">
                            </td>
                            <td class="p-2">
                                <button data-id="${image.id}" class="editButton bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mr-2">Editar</button>
                                <button data-id="${image.id}" class="deleteButton bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                            </td>
                        `;
                        imageList.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error al cargar imágenes:', error));
        };

        loadImages();

        // Manejar el envío del formulario de subida
        const uploadForm = document.getElementById('uploadForm');
        uploadForm.addEventListener('submit', event => {
            event.preventDefault();
            const formData = new FormData(uploadForm);
            fetch(apiUrl, {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    alert('¡Imagen subida con éxito!');
                    uploadForm.reset();
                    loadImages();
                } else {
                    return response.json().then(data => {
                        alert('Error: ' + (data.message || 'Ocurrió un error'));
                    });
                }
            })
            .catch(error => console.error('Error al subir imagen:', error));
        });

        // Manejar clics en botones de editar y eliminar
        document.getElementById('imageList').addEventListener('click', event => {
            const target = event.target;
            const id = target.getAttribute('data-id');

            if (target.classList.contains('editButton')) {
                // Editar imagen
                fetch(`${apiUrl}/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const editForm = document.getElementById('editForm');
                        editForm.name.value = data.name;
                        editForm.id.value = data.id;
                        document.getElementById('editModal').classList.remove('hidden');
                    })
                    .catch(error => console.error('Error al obtener imagen:', error));
            } else if (target.classList.contains('deleteButton')) {
                // Eliminar imagen
                if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
                    fetch(`${apiUrl}/${id}`, {
                        method: 'DELETE',
                    })
                    .then(response => {
                        if (response.ok) {
                            alert('¡Imagen eliminada con éxito!');
                            loadImages();
                        } else {
                            return response.json().then(data => {
                                alert('Error: ' + (data.message || 'Ocurrió un error'));
                            });
                        }
                    })
                    .catch(error => console.error('Error al eliminar imagen:', error));
                }
            }
        });

        // Manejar el envío del formulario de edición
        const editForm = document.getElementById('editForm');
        editForm.addEventListener('submit', event => {
            event.preventDefault();
            const id = editForm.id.value;
            const formData = new FormData(editForm);
            fetch(`${apiUrl}/${id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    alert('¡Imagen actualizada con éxito!');
                    editForm.reset();
                    document.getElementById('editModal').classList.add('hidden');
                    loadImages();
                } else {
                    return response.json().then(data => {
                        alert('Error: ' + (data.message || 'Ocurrió un error'));
                    });
                }
            })
            .catch(error => console.error('Error al actualizar imagen:', error));
        });

        // Manejar cancelación de edición
        document.getElementById('cancelEdit').addEventListener('click', () => {
            document.getElementById('editModal').classList.add('hidden');
            editForm.reset();
        });
    });
</script>
 
@endsection

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection