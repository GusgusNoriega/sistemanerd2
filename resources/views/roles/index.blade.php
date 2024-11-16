@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')

<div x-data="roleManager()" x-init="init()" class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-xl font-bold mb-4">Manage Roles and Permissions</h1>

    <!-- Formulario para agregar roles -->
    <form @submit.prevent="createRole()" class="mb-6">
        <label for="roleName" class="block font-semibold mb-2">Role Name:</label>
        <input 
            type="text" 
            x-model="roleName" 
            class="w-full p-2 border border-gray-300 rounded-lg mb-4"
            placeholder="Enter role name" 
            required>
        <button 
            type="submit" 
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Add Role
        </button>
    </form>

    <!-- Formulario para agregar permisos -->
    <form @submit.prevent="createPermission()" class="mb-6">
        <label for="permissionName" class="block font-semibold mb-2">Permission Name:</label>
        <input 
            type="text" 
            x-model="permissionName" 
            class="w-full p-2 border border-gray-300 rounded-lg mb-4"
            placeholder="Enter permission name" 
            required>
        <button 
            type="submit" 
            class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
            Add Permission
        </button>
    </form>

    <!-- Seleccionar un rol para gestionar sus permisos -->
    <div>
        <label for="roles" class="block font-semibold mb-2">Select Role:</label>
        <select 
            id="roles" 
            x-on:change="loadPermissions()" 
            x-model.number="selectedRole" 
            class="w-full p-2 border border-gray-300 rounded-lg mb-4">
            <option value="" disabled>Select a role</option>
            <template x-for="role in roles" :key="role.id">
                <option :value="role.id" x-text="role.name"></option>
            </template>
        </select>

        <!-- Lista de permisos asignados al rol -->
        <div x-show="permissions.length > 0" class="mt-4">
            <h2 class="text-lg font-semibold mb-2">Manage Permissions</h2>
            <div class="flex flex-wrap gap-4">
                <template x-for="permission in permissions" :key="permission.id">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            x-model="permission.assigned" 
                            class="mr-2">
                        <span x-text="permission.name"></span>
                    </label>
                </template>
            </div>
            <button 
                class="bg-green-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-green-600"
                x-on:click="updatePermissions()">
                Update Permissions
            </button>
        </div>
    </div>

    <!-- Lista de roles con opción para eliminar -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Roles List</h2>
        <ul>
            <template x-for="role in roles" :key="role.id">
                <li class="flex items-center justify-between">
                    <span x-text="role.name"></span>
                    <button @click="deleteRole(role.id)" class="text-red-500 hover:text-red-700">Delete</button>
                </li>
            </template>
        </ul>
    </div>

    <!-- Lista de permisos con opción para eliminar -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Permissions List</h2>
        <ul>
            <template x-for="permission in allPermissions" :key="permission.id">
                <li class="flex items-center justify-between">
                    <span x-text="permission.name"></span>
                    <button @click="deletePermission(permission.id)" class="text-red-500 hover:text-red-700">Delete</button>
                </li>
            </template>
        </ul>
    </div>
</div>

<script>
    function roleManager() {
        return {
            roles: [],
            permissions: [],
            allPermissions: [],
            selectedRole: '',
            roleName: '',
            permissionName: '',

            async fetchRoles() {
                const response = await fetch('/api/roles');
                this.roles = await response.json();

                // Verificar si el rol seleccionado aún existe
                if (!this.roles.some(role => role.id == this.selectedRole)) {
                    this.selectedRole = '';
                    this.permissions = [];
                }
            },

            async fetchPermissions() {
                const response = await fetch('/api/permissions');
                this.allPermissions = await response.json();
            },

            async loadPermissions() {
                if (!this.selectedRole) {
                    this.permissions = [];
                    return;
                }

                const response = await fetch(`/api/roles/${this.selectedRole}`);
                const role = await response.json();

                await this.fetchPermissions();

                this.permissions = this.allPermissions.map(permission => ({
                    ...permission,
                    assigned: role.permissions?.some(rp => rp.id === permission.id) || false
                }));
            },

            async createPermission() {
                const response = await fetch('/api/permissions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: this.permissionName })
                });

                if (response.ok) {
                    alert('Permission added successfully');
                    this.permissionName = '';
                    await this.fetchPermissions();
                    this.loadPermissions();
                } else {
                    const errorData = await response.json();
                    alert('Failed to add permission: ' + (errorData.message || 'Unknown error'));
                }
            },

            async deletePermission(permissionId) {
                const response = await fetch(`/api/permissions/${permissionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    alert('Permission deleted successfully');
                    await this.fetchPermissions();
                    this.loadPermissions();
                } else {
                    const errorData = await response.json();
                    alert('Failed to delete permission: ' + (errorData.message || 'Unknown error'));
                }
            },

            async createRole() {
                const response = await fetch('/api/roles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: this.roleName })
                });

                if (response.ok) {
                    alert('Role added successfully');
                    this.roleName = '';
                    this.fetchRoles();
                } else {
                    const errorData = await response.json();
                    alert('Failed to add role: ' + (errorData.message || 'Unknown error'));
                }
            },

            async updatePermissions() {
                const selectedPermissions = this.permissions
                    .filter(permission => permission.assigned)
                    .map(permission => permission.name);

                const response = await fetch(`/api/roles/${this.selectedRole}/permissions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ permissions: selectedPermissions })
                });

                if (response.ok) {
                    alert('Permissions updated successfully');
                    this.loadPermissions();
                } else {
                    const errorData = await response.json();
                    alert('Failed to update permissions: ' + (errorData.message || 'Unknown error'));
                }
            },

            async deleteRole(roleId) {
                const response = await fetch(`/api/roles/${roleId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    alert('Role deleted successfully');
                    if (this.selectedRole == roleId) {
                        this.selectedRole = '';
                        this.permissions = [];
                    }
                    this.fetchRoles();
                } else {
                    const errorData = await response.json();
                    alert('Failed to delete role: ' + (errorData.message || 'Unknown error'));
                }
            },

            init() {
                this.fetchRoles();
                this.fetchPermissions();
            }
        };
    }
</script>
@endsection

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection