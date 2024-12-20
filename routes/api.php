<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    // Roles
    Route::get('/roles', [RolePermissionController::class, 'getRoles']);
    Route::post('/roles', [RolePermissionController::class, 'createRole']);
    Route::get('/roles/{id}', [RolePermissionController::class, 'getRole']);
    Route::put('/roles/{id}', [RolePermissionController::class, 'updateRole']);
    Route::delete('/roles/{id}', [RolePermissionController::class, 'deleteRole']);

    // Permisos
    Route::get('/permissions', [RolePermissionController::class, 'getPermissions']);
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']);
    Route::put('/permissions/{id}', [RolePermissionController::class, 'updatePermission']);
    Route::delete('/permissions/{id}', [RolePermissionController::class, 'deletePermission']);

    // Asignar permisos a roles
    Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'assignPermissionToRole']);


    //usuarios
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('users/{id}/assign-role', [UserController::class, 'assignRole']);
    Route::post('users/{id}/remove-role', [UserController::class, 'removeRole']);
    Route::get('roles', [RoleController::class, 'index']);

    //imagenes
    Route::apiResource('images', ImageController::class);

    
   
   

    
