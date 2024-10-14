<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Cart');
    }

    // public function addToCart(Request $request, $id) 
    // {
    //     $products = Product::findOrFail($id);
    //     $cart = session()->get('cart', []);

    //     if(isset($cart[$id])) {
    //         $cart[$id]['quantity']++;
    //     } else {
    //         $cart[$id] = [
    //             "product_name" => $products->product_name,
    //             "product_description" =>$products->product_description,
    //             "cost" => $products->cost,
    //             "review" => $products->review,
    //         ];
    //     }
    // }

    // public function removeItem($id) 
    // {
    //     $cart = session()->get('cart', []);
    //     if(isset($cart[$id])) {
    //         unset($cart[$id]);
    //         session()->put('cart', $cart);
    //     }
    //     return redirect()->back()->with('success', 'Product removed from cart successfully!');
    // }
}


