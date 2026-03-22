<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Welcome page
Route::get('/', [MenuController::class, 'welcome'])->name('home');

// QR download
Route::get('/qr-download', function () {
    return redirect()->route('menu');
})->name('qr.download');

// Customer pages
Route::get('/shop', [MenuController::class, 'home'])->name('shop.home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/contact', [MenuController::class, 'contact'])->name('contact');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Order routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/place', [OrderController::class, 'place'])->name('order.place');
Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::resource('menu-items', MenuItemController::class);
    Route::resource('categories', CategoryController::class);
});

Route::get('/track/{token}', [OrderController::class, 'track'])->name('order.track');

Route::get('/dashboard', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';