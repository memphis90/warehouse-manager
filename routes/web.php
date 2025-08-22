<?php

use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\User\RequestController as UserRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Rotte autenticate
Route::middleware('auth')->group(function () {
    
    // Dashboard comune
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
   // Profile routes - AGGIUNGI QUESTE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rotte solo per admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('items', AdminItemController::class);
        Route::resource('requests', AdminRequestController::class);
        Route::resource('items', AdminItemController::class);
        Route::resource('requests', AdminRequestController::class);
        Route::resource('users', AdminUserController::class); // AGGIUNGI QUESTA
    });
    
    // Rotte per utenti normali
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::resource('requests', UserRequestController::class)->only(['index', 'create', 'store', 'show']);
    });
});