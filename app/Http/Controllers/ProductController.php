<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Fetch all products, optionally filter by user
        $products = Product::where('user_id', Auth::id())->get();
        
        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            // Add any other validation rules needed
        ]);

        // Create the product
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'user_id' => Auth::id(), // Set the user ID to the currently authenticated user
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Check if the authenticated user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->withErrors('You do not have permission to edit this product.');
        }

        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Check if the authenticated user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->withErrors('You do not have permission to update this product.');
        }

        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            // Add any other validation rules needed
        ]);

        // Update the product
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Check if the authenticated user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->withErrors('You do not have permission to delete this product.');
        }

        // Delete the product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
