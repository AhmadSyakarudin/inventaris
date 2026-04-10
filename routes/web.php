<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Operator\LendingController;

Route::get('/', function () {
    return view('landing_page');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return view('users.admin.dashboard');
        }

        if ($user->role === 'operator') {
            return view('users.staff.dashboard');
        }

        return redirect('/login');
    })->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class);
    });

    Route::middleware('role:operator')->group(function () {
        Route::resource('lendings', LendingController::class);
        Route::post('/lendings/{lending}/returned', [LendingController::class, 'returned'])->name('lendings.returned');
        Route::get('/staff-users', [UserController::class, 'staffIndex'])->name('staff-users.index');
        Route::get('/staff-users/create', [UserController::class, 'staffCreate'])->name('staff-users.create');
        Route::post('/staff-users', [UserController::class, 'staffStore'])->name('staff-users.store');
        Route::get('/staff-users/{user}/edit', [UserController::class, 'staffEdit'])->name('staff-users.edit');
        Route::put('/staff-users/{user}', [UserController::class, 'staffUpdate'])->name('staff-users.update');
        Route::delete('/staff-users/{user}', [UserController::class, 'staffDestroy'])->name('staff-users.destroy');
    });
});