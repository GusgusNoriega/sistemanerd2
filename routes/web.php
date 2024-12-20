<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/settings', function () {
    return view('auth.settings');
})->name('settings');

Route::get('/settings2', function () {
    return view('auth.settings2');
})->name('settings2');

Route::get('/register2', function () {
    return view('auth.register2');
})->name('register2');


Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');   
});

Route::get('/manage-roles', function () {
    return view('roles.index');
});

Route::get('/users', function () {
    return view('usuarios');
});

Route::get('/imagenes', function () {
    return view('imagenes');
});

Route::get('/prueba', function () {
    return view('prueba');
});

require __DIR__.'/auth.php';