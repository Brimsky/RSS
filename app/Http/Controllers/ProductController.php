<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with optional search.
     */
    public function index(Request $request)
    {
        // Получаем поисковый запрос (если он есть)
        $search = $request->input('search');
        
        // Поиск продуктов по имени или описанию
        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                             ->orWhere('description', 'like', "%$search%");
            })
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'search' => $search, // Вернем поисковый запрос в представление
        ]);
    }

    public function dashboard()
    {
        $userId = auth()->id();
        $products = Product::where('user_id', $userId)->get();

        // Pass the filtered products to the Inertia view
        return Inertia::render('Dashboard', [
            'products' => $products,
        ]);
    }
    
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
        ]);

        // Create the product and assign it to the authenticated user
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
        // Ensure the authenticated user is the owner of the product
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
        // Ensure the authenticated user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->withErrors('You do not have permission to update this product.');
        }

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        // Update the product with validated data
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Ensure the authenticated user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->withErrors('You do not have permission to delete this product.');
        }

        // Delete the product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return Inertia::render('Products/ProductShow', [
            'product' => $product,
        ]);
    }

}
