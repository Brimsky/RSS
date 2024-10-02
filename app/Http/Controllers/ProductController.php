<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_description' => 'required',
            'cost' => 'required|numeric',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::create([
            'product_name' => $request->input('product_name'),
            'product_description' => $request->input('product_description'),
            'cost' => $request->input('cost'),
            'review' => $request->input('review'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_description' => 'required',
            'cost' => 'required|numeric',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update([
            'product_name' => $request->input('product_name'),
            'product_description' => $request->input('product_description'),
            'cost' => $request->input('cost'),
            'review' => $request->input('review'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
