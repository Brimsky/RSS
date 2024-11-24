<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input("search");
        $category = $request->input("category");
        $subcategory = $request->input("subcategory");

        $products = Product::query()
            ->when(!$user->admin, function ($query) use ($user) {
                return $query->where("user_id", $user->id);
            })
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

        $categories = Product::distinct("category")
            ->when(!$user->admin, function ($query) use ($user) {
                return $query->where("user_id", $user->id);
            })
            ->pluck("category");

        $subcategories = Product::when($category, function ($query, $category) {
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

    // In ProductController.php
    public function dashboard()
    {
        $user = auth()->user();
        $data = [
            "user" => $user,
            "savedProducts" => [],
            "products" => [],
            "totalClicks" => 0,
            "productSaves" => [],
        ];

        if ($user->role === "seller") {
            // Seller data
            $data["products"] = Product::where("user_id", $user->id)->get();
            $data["totalClicks"] = Product::where("user_id", $user->id)->sum(
                "clicks"
            );

            // Get saves information for each product
            $data["productSaves"] = Product::where("user_id", $user->id)
                ->withCount("savedBy as saves_count")
                ->with([
                    "savedBy" => function ($query) {
                        $query->select(
                            "users.id",
                            "users.name",
                            "users.email",
                            "saved_products.created_at"
                        );
                    },
                ])
                ->get()
                ->map(function ($product) {
                    return [
                        "id" => $product->id,
                        "name" => $product->name,
                        "saves_count" => $product->saves_count,
                        "savers" => $product->savedBy->map(function ($user) {
                            return [
                                "id" => $user->id,
                                "name" => $user->name,
                                "email" => $user->email,
                                "saved_at" => $user->created_at->format(
                                    "M d, Y H:i"
                                ),
                            ];
                        }),
                    ];
                });
        }

        if ($user->role === "buyer") {
            // Buyer data
            $data["savedProducts"] = $user->saves()->latest()->get();
        }

        return Inertia::render("Dashboard", $data);
    }

    public function registerSave(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!auth()->check()) {
            return response()->json(
                [
                    "message" => "Please login to save products",
                    "saved" => false,
                ],
                401
            );
        }

        $saved = $product->savedBy()->toggle(auth()->id());

        if (count($saved["attached"]) > 0) {
            $product->increment("saves_count");
            $message = "Product saved successfully";
            $isSaved = true;
        } else {
            $product->decrement("saves_count");
            $message = "Product unsaved successfully";
            $isSaved = false;
        }

        return response()->json([
            "message" => $message,
            "saved" => $isSaved,
            "saves_count" => $product->saves_count,
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

        if (
            Auth::user()->role !== "admin" &&
            $product->user_id !== Auth::id()
        ) {
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

        if (
            Auth::user()->role !== "admin" &&
            $product->user_id !== Auth::id()
        ) {
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
                "photos" => "nullable",
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
        if (
            $product->user_id !== Auth::id() &&
            Auth::user()->role !== "admin"
        ) {
            return response()->json(
                [
                    "error" =>
                        "You do not have permission to delete this product.",
                ],
                422
            );
        }

        $product->delete();

        return response()->json([
            "message" => "Product deleted successfully",
        ]);
    }

    public function show($id)
    {
        $product = Product::with('user')->findOrFail($id);
        return Inertia::render("Products/ProductShow", [
            "product" => array_merge($product->toArray(), [
                'seller' => [
                    'id' => $product->user->id,
                    'name' => $product->user->name
                ]
            ])
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
