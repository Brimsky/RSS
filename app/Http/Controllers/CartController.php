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

    // Stripe Checkout process
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Retrieve the cart from the session
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return response()->json(['error' => 'Your cart is empty!'], 400);
        }

        // Prepare Stripe line items and calculate total price
        $lineItems = [];
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Convert price to cents
                ],
                'quantity' => $item['quantity'],
            ];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Create an order in the database
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
        ]);

        // Create individual order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Create the Stripe checkout session
        try {
            $checkoutSession = StripeSession::create([
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
