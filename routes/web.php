<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () { 
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
    Route::post('/login', [AuthController::class, 'login'])->name('login.store'); 
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); 
    Route::post('/register', [AuthController::class, 'register'])->name('register.store'); 
}); 
 
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout'); 

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () { 
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); 
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); 
    Route::resource('products', AdminProductController::class)->except('show'); 
}); 
