<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;

// Public routes
Route::get("/", function () {
    $search = request("search");
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
        "search" => $search,
    ]);
})->name("home");

Route::get("/delivery", [DeliveryController::class, "index"])->name(
    "delivery.index"
);
Route::get("/api/categories", [ProductController::class, "getCategories"]);

// Authentication required routes
Route::middleware(["auth"])->group(function () {
    Route::get("/dashboard", [ProductController::class, "dashboard"])->name(
        "dashboard"
    );

    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::match(["patch", "post"], "/profile", [
        ProfileController::class,
        "update",
    ])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );

    Route::get("/products", [ProductController::class, "index"])->name(
        "products.index"
    );
    Route::get("/products/create", [ProductController::class, "create"])->name(
        "products.create"
    );
    Route::post("/products", [ProductController::class, "store"])->name(
        "products.store"
    );
    Route::get("/products/{product}/edit", [
        ProductController::class,
        "edit",
    ])->name("products.edit");
    Route::put("/products/{product}", [
        ProductController::class,
        "update",
    ])->name("products.update");
    Route::delete("/products/{product}", [
        ProductController::class,
        "destroy",
    ])->name("products.destroy");
    Route::post("/products/{product}/register-click", [
        ProductController::class,
        "registerClick",
    ])->name("products.registerClick");
    Route::post("/products/{id}/save", [
        ProductController::class,
        "registerSave",
    ])->name("products.save");
    Route::get("/cart", [CartController::class, "index"])->name("cart");
    Route::post("/cart/add", [CartController::class, "add"])->name("cart.add");
    Route::post("/cart/update", [CartController::class, "update"])->name(
        "cart.update"
    );
    Route::post("/cart/remove", [CartController::class, "remove"])->name(
        "cart.remove"
    );

    Route::post("/api/create-checkout-session", [
        CartController::class,
        "createCheckoutSession",
    ]);
    Route::post("/delivery/store", [DeliveryController::class, "store"])->name(
        "delivery.store"
    );

    Route::get("/checkout/success", [
        CheckoutController::class,
        "success",
    ])->name("success");
    Route::get("/checkout/cancel", [CheckoutController::class, "cancel"])->name(
        "checkout.cancel"
    );
});
//nekustinat no vietas
//obligati but virs categroijam un subkategroijam
Route::post("/cart/add", [CartController::class, "add"])->name("cart.add");

// These routes should be at the bottom to avoid conflicts
Route::get("/categories/{category}", [
    ProductController::class,
    "subcategories",
])->name("products.subcategories");
Route::get("/products/{category}/{subcategory}", [
    ProductController::class,
    "listings",
])->name("products.listings");
Route::get("/products/{id}", [ProductController::class, "show"])->name(
    "products.show"
);
Route::prefix("admin")->group(function () {
    Route::get("/", [AdminController::class, "index"])->name("admin.index");

    // User management routes
    Route::put("/users/{user}", [AdminController::class, "updateUser"])->name(
        "admin.users.update"
    );
    Route::delete("/users/{user}", [
        AdminController::class,
        "destroyUser",
    ])->name("admin.users.destroy");
    Route::put("/products/{product}", [
        AdminController::class,
        "updateProduct",
    ])->name("admin.products.update");
    Route::post("/products", [AdminController::class, "storeProduct"])->name(
        "admin.products.store"
    );
    Route::delete("/products/{product}", [
        AdminController::class,
        "destroyProduct",
    ])->name("admin.products.destroy");

    // Product management routes
    Route::resource("products", ProductController::class)->names([
        "index" => "admin.products.index",
        "create" => "admin.products.create",
        "store" => "admin.products.store",
        "edit" => "admin.products.edit",
        "update" => "admin.products.update",
        "destroy" => "admin.products.destroy",
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/users', [ChatController::class, 'getAllUsers'])->name('chat.users');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::get('/chat/user/{id}', [ChatController::class, 'getMessagesForUser'])->name('chat.user.messages');
});

Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\MessageSent('Test message!'));
    return 'Broadcast sent!';
});


require __DIR__ . "/auth.php";
