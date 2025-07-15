<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ProductAttributeController extends Controller
{
   
    public function index(): View
    {
        $productAttributes = ProductAttribute::with('attributeValues')->latest()->paginate(10);
        return view('product_attributes.index', compact('productAttributes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:product_attributes,name',
            'type' => 'required|string|in:text,color,number,dropdown', // Define allowed types
        ]);

            ProductAttribute::create($validatedData);
            return redirect()->route('product_attributes.index')
                             ->with('success', 'Product attribute created successfully.');
        
    }

    public function show(ProductAttribute $productAttribute): View
    {
        $productAttribute->load('attributeValues'); 
        return view('product_attributes.show', compact('productAttribute'));
    }

    public function edit(ProductAttribute $productAttribute): View
    {
        return view('product_attributes.edit', compact('productAttribute'));
    }

    public function update(Request $request, ProductAttribute $productAttribute): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:product_attributes,name,' . $productAttribute->id,
            'type' => 'required|string|in:text,color,number,dropdown',
        ]);

            $productAttribute->update($validatedData);
            return redirect()->route('product_attributes.index')
                             ->with('success', 'Product attribute updated successfully.');
        
    }

    public function destroy(ProductAttribute $productAttribute): RedirectResponse
    {
            $productAttribute->delete(); 
            return redirect()->route('product_attributes.index')
                             ->with('success', 'Product attribute deleted successfully.');
    }
}
