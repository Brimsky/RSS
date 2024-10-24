<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render("Admin/Index", [
            "products" => Product::all(),
            "users" => User::all(),
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email," . $user->id,
            "role" => "required|in:buyer,seller,admin",
        ]);

        $user->update($validated);

        return redirect()->back()->with("success", "User updated successfully");
    }

    public function destroyUser(User $user)
    {
        if ($user->role === "admin") {
            return back()->with("error", "Cannot delete admin user");
        }

        $user->delete();
        return back()->with("success", "User deleted successfully");
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric|min:0",
            "description" => "required|string",
            "image" => "nullable|string",
        ]);

        $product->update($validated);

        return back()->with([
            "success" => "Product updated successfully",
            "products" => Product::all(),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric|min:0",
            "description" => "required|string",
            "image" => "nullable|string",
        ]);

        Product::create($validated);

        return back()->with([
            "success" => "Product created successfully",
            "products" => Product::all(),
        ]);
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
    }
}
