<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Display the cart
    public function index()
    {
        $cartItems = session()->get('cart', []);
        return Inertia::render('Cart', ['cartItems' => $cartItems]);
    }

    // Add a product to the cart
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

        // Add quantity if the product is already in the cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "id"          => $product->id,
                "name"        => $product->name,
                "quantity"    => 1,
                "price"       => $product->price,
                "description" => $product->description,
                "photos"      => $product->photos,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Update product quantity in the cart
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    // Remove product from the cart
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
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    
        $cartItems = $request->input('items', []);
        $deliveryCost = $request->input('deliveryCost', 0); 
    
        // Calculate total price (items + delivery)
        $totalPrice = array_reduce($cartItems, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0) + $deliveryCost;
    
        
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
            'status' => 'pending', 
            'payment_method' => 'stripe',
            'order_date' => now(),
        ]);
    
        
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item['id'], 
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, 
                ],
                'quantity' => $item['quantity'],
            ];
        }
    
        if ($deliveryCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Delivery Cost',
                    ],
                    'unit_amount' => $deliveryCost * 100,
                ],
                'quantity' => 1,
            ];
        }
    
        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => url('/checkout/success'),
                'cancel_url' => url('/checkout/cancel'),
            ]);
    
            return response()->json(['id' => $checkoutSession->id]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => 'Stripe error: ' . $e->getMessage()], 500);
        }
    }
}
