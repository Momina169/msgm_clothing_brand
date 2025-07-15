<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str; 

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        
                $categories = Category::all();

        $products = Product::with(['category', 'inventory', 'images'])
                            ->latest()
                            ->paginate(10);
        return view('products.index', compact('products', 'categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|unique:products|max:255',
            'is_active' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);


        $product = Product::create($validatedData);

        //  multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $imageFile) {
                $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'images/products/' . $imageName; // Path to store in DB

                $imageFile->move(public_path('images/products'), $imageName);

                if ($key == 0) {
                    $product->update(['image' => $imagePath]);
                }

                // Save all images to separate product_images table
                $product->images()->create([
                    'file_name' => $imagePath,
                ]);
            }
        }

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'inventory', 'reviews', 'variants.attributeValues.productAttribute', 'images']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        $product->load(['category', 'inventory', 'images']); // Load existing images
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id . '|max:255',
            'is_active' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'existing_images_to_delete' => 'nullable|array', 
            'existing_images_to_delete.*' => 'exists:product_images,id',
        ]);

        $product->update($validatedData);

        //  deletion of existing images
        if (isset($validatedData['existing_images_to_delete'])) {
            foreach ($validatedData['existing_images_to_delete'] as $imageId) {
                $imageToDelete = $product->images()->find($imageId);
                if ($imageToDelete) {

                    if (file_exists(public_path($imageToDelete->file_name))) {
                        unlink(public_path($imageToDelete->file_name));
                    }
                    $imageToDelete->delete();

                    if ($product->image === $imageToDelete->file_name) {
                        $product->update(['image' => null]);
                    }
                }
            }
        }

        // set the first available image as the main image.
        if (is_null($product->image) && $product->images->isNotEmpty()) {
            $product->update(['image' => $product->images->first()->file_name]);
        }

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $image) {
            if (file_exists(public_path($image->file_name))) {
                unlink(public_path($image->file_name));
            }
            $image->delete(); 
        }

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }


        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }

     public function details(Product $product)
    {
        $product->load(['category','images','variants.attributeValues.productAttribute',
        'reviews.user' => function ($query) {
                $query->where('is_approved', true);
            }
        ]);
        return view('frontend.product.show', compact('product'));
    }


}
