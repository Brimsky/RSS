<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;


class CartController extends Controller
{
    // Display the cart contents
    public function index()
    {
        $cartItems = session()->get('cart', []);
        return inertia('Cart', ['cartItems' => $cartItems]);
    }

    // Add a product to the cart
    public function add(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        // Retrieve the cart from the session; ensure it's an array
        $cart = session()->get('cart', []);
        if (!is_array($cart)) {
            $cart = [];
        }

        // Check if the product is already in the cart
        if (isset($cart[$productId])) {
            // Ensure 'quantity' is set and is numeric
            if (!isset($cart[$productId]['quantity']) || !is_numeric($cart[$productId]['quantity'])) {
                $cart[$productId]['quantity'] = 0;
            }
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "id"          => $product->id,
                "name"        => $product->name,
                "quantity"    => 1, // Initialize quantity to 1
                "price"       => $product->price,
                "description" => $product->description,
                // Add more fields if needed
            ];
        }

        // Save the cart back to the session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Update cart item quantity
    public function update(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        if (isset($cart[$productId]) && is_array($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        // Return a 303 redirect
        return redirect()->back(303)->with('success', 'Cart updated!');
    }


    // Remove a product from the cart
    public function remove(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Product removed from cart!');
    }
}
