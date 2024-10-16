<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Checkout\Session;


class CartController extends Controller
{
    // Izvada grozu
    public function index()
    {
        $cartItems = session()->get('cart', []);
        return inertia('Cart', ['cartItems' => $cartItems]);
    }

    // Pievienot produktu grozā
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);
        if (!is_array($cart)) {
            $cart = [];
        }

        if (isset($cart[$productId])) {
            if (!isset($cart[$productId]['quantity']) || !is_numeric($cart[$productId]['quantity'])) {
                $cart[$productId]['quantity'] = 0;
            }
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "id"          => $product->id,
                "name"        => $product->name,
                "quantity"    => 1, 
                "price"       => $product->price,
                "description" => $product->description,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Daudzumu atjaunot
    public function update(Request $request)
    {
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

        return redirect()->back(303)->with('success', 'Cart updated!');
    }


    // Noņemt produktu
    public function remove(Request $request)
    {
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

    // Stripe
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    
        $cartItems = $request->input('items');
    
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Convert to cents
                ],
                'quantity' => $item['quantity'],
            ];
        }
    
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => url('/checkout/success'),
            'cancel_url' => url('/checkout/cancel'),
        ]);
    
        return response()->json(['id' => $checkoutSession->id]);
    }
}
