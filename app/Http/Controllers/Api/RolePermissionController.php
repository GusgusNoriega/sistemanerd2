<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Obtener todos los roles
    public function getRoles()
    {
        return response()->json(Role::all(), 200);
    }

    // Crear un nuevo rol
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    // Obtener un rol específico
    public function getRole($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
    
        return response()->json($role, 200);
    }

    // Actualizar un rol
    public function updateRole(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
    }

    // Eliminar un rol
    public function deleteRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }

    // Obtener todos los permisos
    public function getPermissions()
    {
        return response()->json(Permission::all(), 200);
    }

    // Crear un permiso
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission created successfully', 'permission' => $permission], 201);
    }

    // Asignar permisos a un rol
    public function assignPermissionToRole(Request $request, $roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json(['message' => 'Permissions assigned successfully'], 200);
    }

    // Actualizar un permiso
        public function updatePermission(Request $request, $id)
        {
            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['message' => 'Permission not found'], 404);
            }

            $request->validate([
                'name' => 'required|unique:permissions,name,' . $permission->id,
            ]);

            $permission->update(['name' => $request->name]);

            return response()->json(['message' => 'Permission updated successfully', 'permission' => $permission], 200);
        }

        // Eliminar un permiso
        public function deletePermission($id)
        {
            $permission = Permission::find($id);
        
            if (!$permission) {
                return response()->json(['message' => 'Permission not found'], 404);
            }
        
            // Desasociar el permiso de todos los roles antes de eliminarlo
            $permission->roles()->detach();
        
            $permission->delete();
        
            return response()->json(['message' => 'Permission deleted successfully'], 200);
        }
}
