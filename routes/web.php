<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('test_login');
});
Route::get('/login', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->is_admin) return redirect('/admin');
        if ($user->is_dispatcher) return redirect('/dispatcher');
        return redirect('/');
    }
    return view('login');
})->name('login');

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);