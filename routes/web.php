<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CartController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\ProductController;

// Ensure this is inside your auth middleware group
Route::middleware(['auth'])->group(function () {
    // Route to view all products (GET)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Route to create a new product (GET)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    // Route to store a new product (POST)
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // Route to edit a product (GET)
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // Route to update a product (PUT/PATCH)
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Route to delete a product (DELETE)
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


// Prieks cart nākotnē - KK
// Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
//     Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
//     Route::post('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
//     Route::get('/checkout', [StripePaymentController::class, 'handleCheckout'])->name('checkout.handle');

// Cart routings - piekluve tikai caur login
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
});


require __DIR__.'/auth.php';
