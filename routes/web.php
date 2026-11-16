<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController; 
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ShippingServiceController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;// Sesuaikan jika AdminProductController ada di dalam folder Admin
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

Route::get('/home', function () {
    return view('layouts.app'); // Sesuaikan dengan view utama Anda
})->name('home');

Route::middleware('guest')->group(function () { 
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
    Route::post('/login', [AuthController::class, 'login'])->name('login.store'); 
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); 
    Route::post('/register', [AuthController::class, 'register'])->name('register.store'); 
}); 
 
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout'); 

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () { 
    Route::get('/dashboard', function () {
        return view('layouts.admin'); // Pastikan file view admin/dashboard.blade.php ada
    })->name('dashboard');
    
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); 
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); 
    Route::resource('products', ProductController::class)->except('show'); 

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');

    Route::get('/shipping', [ShippingServiceController::class, 'index'])->name('shipping.index'); 
    Route::post('/shipping', [ShippingServiceController::class, 'store'])->name('shipping.store'); 
    Route::put('/shipping/{shipping}', [ShippingServiceController::class, 'update'])->name('shipping.update'); 
    Route::delete('/shipping/{shipping}', [ShippingServiceController::class, 'destroy'])->name('shipping.destroy'); 
    Route::resource('products', ProductController::class)->except('show'); 
}); 

// Rute untuk Keranjang Belanja (Customer)
Route::get('/cart', function () {
    return view('cart.index'); // Sesuaikan dengan view keranjang Anda
})->name('cart.index')->middleware('auth');

// Rute untuk Riwayat Pesanan (Customer)
Route::get('/orders', function () {
    return view('orders.index'); // Sesuaikan dengan view pesanan Anda
})->name('orders.index')->middleware('auth');

// Rute utama (root)
Route::get('/', [ProductCatalogController::class, 'index'])->name('shop.index');

// Tambahkan rute ini agar URL /home juga membuka katalog produk yang sama
Route::get('/home', [ProductCatalogController::class, 'index'])->name('home');

Route::get('/product/{product:slug}', [ProductCatalogController::class, 'show'])->name('shop.show');

Route::middleware(['auth','role:customer'])->group(function () { 
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index'); 
    Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store'); 
    Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update'); 
    Route::delete('/keranjang/{product}', [CartController::class, 'destroy'])->name('cart.destroy'); 
}); 
