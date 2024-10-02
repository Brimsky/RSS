<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // Add this line to import the Product model

class EnsureProductOwner
{
    public function handle($request, Closure $next, $product_id)
    {
        $product = Product::find($product_id);
        
        if (!$product) {
            abort(404);
        }

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        return $next($request);
    }
}
