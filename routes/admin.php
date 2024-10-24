<?php

use App\Http\Controllers\AdminController;

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
