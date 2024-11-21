@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')
  <!-- Sección principal -->
  <section id="app">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Gestión de Usuarios</h1>
            <!-- Botón para agregar un nuevo usuario -->
            <button id="addUserBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">Agregar Nuevo Usuario</button>
            <!-- Lista de usuarios -->
            <div id="userList">
                <!-- Los usuarios se mostrarán aquí -->
            </div>
        </div>
    </section>

    <!-- Código JavaScript -->
    <script>
        // URL base de la API
        const apiUrl = '/api'; // Asegúrate de ajustar la URL según tu configuración

        // Función para obtener y mostrar usuarios
        async function fetchUsers() {
            try {
                const response = await fetch(`${apiUrl}/users`);
                const users = await response.json();

                const userList = document.getElementById('userList');
                userList.innerHTML = '';

                users.forEach(user => {
                    const userCard = document.createElement('div');
                    userCard.className = 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4';

                    userCard.innerHTML = `
                        <h2 class="text-xl font-bold mb-2">${user.name}</h2>
                        <p class="mb-2">Email: ${user.email}</p>
                        <p class="mb-2">Roles:</p>
                        <ul class="mb-2">
                            ${user.roles.map(role => `
                                <li class="flex items-center">
                                    <span>${role.name}</span>
                                    <button class="removeRoleBtn text-red-500 hover:text-red-700 font-bold ml-2" data-user-id="${user.id}" data-role-name="${role.name}">Eliminar</button>
                                </li>
                            `).join('')}
                        </ul>
                        <button class="editUserBtn bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2" data-id="${user.id}">Editar</button>
                        <button class="deleteUserBtn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2" data-id="${user.id}">Eliminar</button>
                        <button class="assignRoleBtn bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" data-id="${user.id}">Asignar Rol</button>
                    `;

                    userList.appendChild(userCard);
                });

                // Agregar event listeners
                document.querySelectorAll('.editUserBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-id');
                        showEditUserForm(userId);
                    });
                });

                document.querySelectorAll('.deleteUserBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-id');
                        deleteUser(userId);
                    });
                });

                document.querySelectorAll('.assignRoleBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-id');
                        showAssignRoleForm(userId);
                    });
                });

                document.querySelectorAll('.removeRoleBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-user-id');
                        const roleName = button.getAttribute('data-role-name');
                        removeRoleFromUser(userId, roleName);
                    });
                });

            } catch (error) {
                console.error('Error al obtener usuarios:', error);
            }
        }

        // Mostrar formulario para agregar usuario
        function showAddUserForm() {
            if (document.getElementById('addUserForm')) {
                return;
            }

            const container = document.querySelector('.container');

            const form = document.createElement('form');
            form.id = 'addUserForm';
            form.className = 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4';

            form.innerHTML = `
                <h2 class="text-xl font-bold mb-4">Agregar Nuevo Usuario</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nombre
                    </label>
                    <input id="name" type="text" placeholder="Nombre" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input id="email" type="email" placeholder="Email" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input id="password" type="password" placeholder="Contraseña" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Agregar Usuario
                    </button>
                    <button id="cancelAddUser" type="button"
                            class="text-red-500 hover:text-red-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancelar
                    </button>
                </div>
            `;

            container.insertBefore(form, container.firstChild);

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const userData = {
                    name: form.querySelector('#name').value,
                    email: form.querySelector('#email').value,
                    password: form.querySelector('#password').value,
                };

                await addUser(userData);

                form.remove();
                fetchUsers();
            });

            document.getElementById('cancelAddUser').addEventListener('click', () => {
                form.remove();
            });
        }

        async function addUser(userData) {
            try {
                const response = await fetch(`${apiUrl}/users`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(userData),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Error al agregar usuario: ' + JSON.stringify(errorData));
                } else {
                    alert('¡Usuario agregado exitosamente!');
                }
            } catch (error) {
                console.error('Error al agregar usuario:', error);
            }
        }

        // Mostrar formulario para editar usuario
        function showEditUserForm(userId) {
            if (document.getElementById('editUserForm')) {
                return;
            }

            const container = document.querySelector('.container');

            fetch(`${apiUrl}/users/${userId}`)
                .then(response => response.json())
                .then(user => {
                    const form = document.createElement('form');
                    form.id = 'editUserForm';
                    form.className = 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4';

                    form.innerHTML = `
                        <h2 class="text-xl font-bold mb-4">Editar Usuario</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="editName">
                                Nombre
                            </label>
                            <input value="${user.name}" id="editName" type="text" placeholder="Nombre" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="editEmail">
                                Email
                            </label>
                            <input value="${user.email}" id="editEmail" type="email" placeholder="Email" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="editPassword">
                                Contraseña (Dejar en blanco para mantener la actual)
                            </label>
                            <input id="editPassword" type="password" placeholder="Nueva Contraseña"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Usuario
                            </button>
                            <button id="cancelEditUser" type="button"
                                    class="text-red-500 hover:text-red-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </button>
                        </div>
                    `;

                    container.insertBefore(form, container.firstChild);

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const userData = {
                            name: form.querySelector('#editName').value,
                            email: form.querySelector('#editEmail').value,
                        };

                        const password = form.querySelector('#editPassword').value;
                        if (password) {
                            userData.password = password;
                        }

                        await updateUser(userId, userData);

                        form.remove();
                        fetchUsers();
                    });

                    document.getElementById('cancelEditUser').addEventListener('click', () => {
                        form.remove();
                    });
                })
                .catch(error => {
                    console.error('Error al obtener datos del usuario:', error);
                });
        }

        async function updateUser(userId, userData) {
            try {
                const response = await fetch(`${apiUrl}/users/${userId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(userData),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Error al actualizar usuario: ' + JSON.stringify(errorData));
                } else {
                    alert('¡Usuario actualizado exitosamente!');
                }
            } catch (error) {
                console.error('Error al actualizar usuario:', error);
            }
        }

        // Eliminar usuario
        async function deleteUser(userId) {
            if (!confirm('¿Estás seguro de eliminar este usuario?')) {
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/users/${userId}`, {
                    method: 'DELETE',
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Error al eliminar usuario: ' + JSON.stringify(errorData));
                } else {
                    alert('¡Usuario eliminado exitosamente!');
                    fetchUsers();
                }
            } catch (error) {
                console.error('Error al eliminar usuario:', error);
            }
        }

        // Mostrar formulario para asignar rol
        function showAssignRoleForm(userId) {
            if (document.getElementById('assignRoleForm')) {
                return;
            }

            const container = document.querySelector('.container');

            fetch(`${apiUrl}/roles`)
                .then(response => response.json())
                .then(roles => {
                    const form = document.createElement('form');
                    form.id = 'assignRoleForm';
                    form.className = 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4';

                    let options = roles.map(role => `<option value="${role.name}">${role.name}</option>`).join('');

                    form.innerHTML = `
                        <h2 class="text-xl font-bold mb-4">Asignar Rol al Usuario</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="roleSelect">
                                Selecciona un Rol
                            </label>
                            <select id="roleSelect"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                ${options}
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Asignar Rol
                            </button>
                            <button id="cancelAssignRole" type="button"
                                    class="text-red-500 hover:text-red-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </button>
                        </div>
                    `;

                    container.insertBefore(form, container.firstChild);

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const roleName = form.querySelector('#roleSelect').value;

                        await assignRoleToUser(userId, roleName);

                        form.remove();
                        fetchUsers();
                    });

                    document.getElementById('cancelAssignRole').addEventListener('click', () => {
                        form.remove();
                    });
                })
                .catch(error => {
                    console.error('Error al obtener roles:', error);
                });
        }

        async function assignRoleToUser(userId, roleName) {
            try {
                const response = await fetch(`${apiUrl}/users/${userId}/assign-role`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ role: roleName }),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Error al asignar rol: ' + JSON.stringify(errorData));
                } else {
                    alert('¡Rol asignado exitosamente!');
                }
            } catch (error) {
                console.error('Error al asignar rol:', error);
            }
        }

        // Eliminar rol de usuario
        async function removeRoleFromUser(userId, roleName) {
            if (!confirm(`¿Estás seguro de eliminar el rol "${roleName}" de este usuario?`)) {
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/users/${userId}/remove-role`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ role: roleName }),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Error al eliminar rol: ' + JSON.stringify(errorData));
                } else {
                    alert('¡Rol eliminado exitosamente!');
                    fetchUsers();
                }
            } catch (error) {
                console.error('Error al eliminar rol:', error);
            }
        }

        // Event listeners
        document.getElementById('addUserBtn').addEventListener('click', showAddUserForm);

        // Cargar usuarios al cargar la página
        fetchUsers();
    </script>
@endsection

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection