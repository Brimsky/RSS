<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;

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

    // In your web.php, add with your other product routes
    // Route::post("/products/{id}/save", [
    //     ProductController::class,
    //     "registerSave",
    // ])
    //     ->name("products.save")
    //     ->middleware("auth");

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

    // Route to save/unsave a product
    Route::post("/products/{id}/save", [
        ProductController::class,
        "registerSave",
    ])->name("products.save");
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

Route::get("/chat/{seller?}", function ($seller = null) {
    return Inertia::render("Chat", ["seller" => $seller]);
})->name("chat");
Route::get("/messages", [MessageController::class, "index"]);
Route::post("/messages", [MessageController::class, "store"]);

require __DIR__ . "/auth.php";
