<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Product; 
use App\Models\ProductAttribute;
use App\Models\AttributeValue; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str; 

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productVariants = ProductVariant::with(['product', 'attributeValues.productAttribute'])
                                        ->latest()
                                        ->paginate(10);

        $products = Product::all();
        $productAttributes = ProductAttribute::with('attributeValues')->get();

        return view('product_variants.index', compact('productVariants', 'products', 'productAttributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'stock_level' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attribute_values' => 'required|array|min:1', 
            'attribute_values.*' => 'exists:attribute_values,id',
        ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'images/variants/' . $imageName;
                $imageFile->move(public_path('images/variants'), $imageName);
            }

            $productVariant = ProductVariant::create([
                'product_id' => $validatedData['product_id'],
                'sku' => $validatedData['sku'],
                'price' => $validatedData['price'],
                'stock_level' => $validatedData['stock_level'],
                'image' => $imagePath,
            ]);

            // Attach selected attribute values to the product variant
            $productVariant->attributeValues()->attach($validatedData['attribute_values']);

            return redirect()->route('product_variants.index')
                             ->with('success', 'Product variant created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $productVariant): View
    {
        $productVariant->load(['product', 'attributeValues']); 
        $products = Product::all(); 
        $productAttributes = ProductAttribute::with('attributeValues')->get(); 

        return view('product_variants.edit', compact('productVariant', 'products', 'productAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $productVariant): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'nullable|string|max:255|unique:product_variants,sku,' . $productVariant->id, // Unique excluding current
            'price' => 'required|numeric|min:0',
            'stock_level' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attribute_values' => 'required|array|min:1',
            'attribute_values.*' => 'exists:attribute_values,id',
        ]);

        
            $imagePath = $productVariant->image; 
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $imageFile = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'images/variants/' . $imageName;
                $imageFile->move(public_path('images/variants'), $imageName);
            }
             elseif ($request->input('clear_image')) { 
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $imagePath = null;
            }

            $productVariant->update([
                'product_id' => $validatedData['product_id'],
                'sku' => $validatedData['sku'],
                'price' => $validatedData['price'],
                'stock_level' => $validatedData['stock_level'],
                'image' => $imagePath,
            ]);

            //  (detach old ones, attach new ones)
            $productVariant->attributeValues()->sync($validatedData['attribute_values']);

            return redirect()->route('product_variants.index')
                             ->with('success', 'Product variant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant): RedirectResponse
    {

            if ($productVariant->image && file_exists(public_path($productVariant->image))) {
                unlink(public_path($productVariant->image));
            }

            $productVariant->delete(); 

            return redirect()->route('product_variants.index')
                             ->with('success', 'Product variant deleted successfully.');
    }
}
