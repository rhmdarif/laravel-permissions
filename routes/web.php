<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::as('anggota.')->group(function() {
   Route::get('home', [App\Http\Controllers\Anggota\HomeController::class, 'home'])->name('home');
});

Route::as('admin.')->prefix('admin')->group(function() {
   Route::get('home', [App\Http\Controllers\Admin\HomeController::class, 'home'])->name('home');


   Route::resource('permission', \App\Http\Controllers\Admin\PermissionController::class);
   Route::any("role/{role}/permissions-checkbox", [\App\Http\Controllers\Admin\RoleController::class, 'permissions'])->name('role.permissions-checkbox');
   Route::post("role/{role}/assign-permissions", [\App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('role.assign-permissions');
   Route::resource('role', \App\Http\Controllers\Admin\RoleController::class);
   Route::any("user/{user}/permissions-checkbox", [\App\Http\Controllers\Admin\UserController::class, 'permissions'])->name('user.permissions-checkbox');
   Route::post("user/{user}/assign-permissions", [\App\Http\Controllers\Admin\UserController::class, 'assignPermissions'])->name('user.assign-permissions');
   Route::post("user/{user}/restore", [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('user.restore');
   Route::get('user/role/{role_id}', [\App\Http\Controllers\Admin\UserController::class, 'index_by_level'])->name('user.index_by_level');
   Route::resource('user', \App\Http\Controllers\Admin\UserController::class);

});

require __DIR__.'/auth.php';
