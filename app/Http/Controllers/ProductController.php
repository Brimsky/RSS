<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this import

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input("search");
        $category = $request->input("category");
        $subcategory = $request->input("subcategory");

        $products = Product::query()
            ->where("user_id", $user->id) // Filter products by user_id
            ->when($search, function ($query, $search) {
                return $query
                    ->where("name", "like", "%$search%")
                    ->orWhere("description", "like", "%$search%");
            })
            ->when($category, function ($query, $category) {
                return $query->where("category", $category);
            })
            ->when($subcategory, function ($query, $subcategory) {
                return $query->where("subcategory", $subcategory);
            })
            ->get();

        $categories = Product::where("user_id", $user->id)
            ->distinct("category")
            ->pluck("category");
        $subcategories = Product::where("user_id", $user->id)
            ->when($category, function ($query, $category) {
                return $query->where("category", $category);
            })
            ->distinct("subcategory")
            ->pluck("subcategory");

        return Inertia::render("Products/Index", [
            "products" => $products,
            "search" => $search,
            "categories" => $categories,
            "subcategories" => $subcategories,
            "selectedCategory" => $category,
            "selectedSubcategory" => $subcategory,
        ]);
    }

    public function subcategories($category)
    {
        $subcategories = Product::where("category", $category)
            ->distinct("subcategory")
            ->pluck("subcategory");

        return Inertia::render("Products/ProductSubcategory", [
            "category" => $category,
            "subcategories" => $subcategories,
            "message" => $subcategories->isEmpty()
                ? "No subcategories found for this category."
                : null,
        ]);
    }

    public function listings($category, $subcategory)
    {
        $products = Product::where("category", $category)
            ->where("subcategory", $subcategory)
            ->get();

        return Inertia::render("Products/ListingProducts", [
            "category" => $category,
            "subcategory" => $subcategory,
            "products" => $products,
        ]);
    }

    public function dashboard()
    {
        $userId = auth()->id();
        $products = Product::where("user_id", $userId)->get();
        $totalClicks = Product::where("user_id", $userId)->sum("clicks");

        return Inertia::render("Dashboard", [
            "products" => $products,
            "totalClicks" => $totalClicks,
        ]);
    }

    public function create()
    {
        return Inertia::render("Products/Create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric",
            "description" => "nullable|string",
            "category" => "required|string",
            "subcategory" => "required|string",
            "condition" => "required|string",
            "location" => "required|string",
        ]);

        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description,
            "category" => $request->category,
            "subcategory" => $request->subcategory,
            "condition" => $request->condition,
            "location" => $request->location,
            "user_id" => Auth::id(),
        ]);

        // If you're handling photo uploads, process them here

        return redirect()
            ->route("products.index")
            ->with("success", "Product created successfully.");
    }

    public function edit(Product $product)
    {
        Log::info("Accessing edit method", [
            "product_id" => $product->id,
            "user_id" => Auth::id(),
            "product_user_id" => $product->user_id,
        ]);

        if ($product->user_id !== Auth::id()) {
            Log::warning("Unauthorized edit attempt", [
                "user_id" => Auth::id(),
                "product_id" => $product->id,
            ]);
            return redirect()
                ->route("products.index")
                ->with(
                    "error",
                    "You do not have permission to edit this product."
                );
        }

        return Inertia::render("Products/Edit", [
            "product" => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        Log::info("Accessing update method", [
            "product_id" => $product->id,
            "user_id" => Auth::id(),
            "product_user_id" => $product->user_id,
            "request_data" => $request->all(),
        ]);

        if ($product->user_id !== Auth::id()) {
            Log::warning("Unauthorized update attempt", [
                "user_id" => Auth::id(),
                "product_id" => $product->id,
            ]);
            return redirect()
                ->route("products.index")
                ->with(
                    "error",
                    "You do not have permission to update this product."
                );
        }

        try {
            $validated = $request->validate([
                "name" => "required|string|max:255",
                "price" => "required|numeric",
                "description" => "nullable|string",
                "category" => "required|string",
                "subcategory" => "required|string",
                "condition" => "required|string",
                "location" => "required|string",
                "photos" => "nullable", // Make it nullable and remove JSON validation
            ]);

            Log::info("Validation passed", [
                "product_id" => $product->id,
                "validated_data" => $validated,
            ]);

            $product->fill($validated);
            $isDirty = $product->isDirty();
            $changedFields = $product->getDirty();

            $product->save();

            Log::info("Product update attempt completed", [
                "product_id" => $product->id,
                "is_dirty" => $isDirty,
                "changed_fields" => $changedFields,
            ]);

            if ($isDirty) {
                return redirect()
                    ->route("products.index")
                    ->with("success", "Product updated successfully.");
            } else {
                return redirect()
                    ->route("products.index")
                    ->with("info", "No changes were made to the product.");
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation failed", [
                "product_id" => $product->id,
                "errors" => $e->errors(),
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error updating product", [
                "product_id" => $product->id,
                "error" => $e->getMessage(),
            ]);
            return back()
                ->withErrors([
                    "update_error" =>
                        "An error occurred while updating the product: " .
                        $e->getMessage(),
                ])
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            return redirect()
                ->route("products.index")
                ->with(
                    "error",
                    "You do not have permission to delete this product."
                );
        }

        $product->delete();

        return redirect()
            ->route("products.index")
            ->with("success", "Product deleted successfully.");
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render("Products/ProductShow", [
            "product" => $product,
        ]);
    }

    public function registerClick(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            $product->increment("clicks");
        }

        return response()->json(["message" => "Click registered successfully"]);
    }

    public function getCategories()
    {
        $categories = Product::distinct("category")->pluck("category");
        return response()->json($categories);
    }
}
