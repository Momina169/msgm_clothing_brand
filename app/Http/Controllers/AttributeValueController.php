<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\ProductAttribute; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class AttributeValueController extends Controller
{
    
    public function index()
    {
        $attributeValues = AttributeValue::with('productAttribute')->latest()->paginate(10);
        $productAttributes = ProductAttribute::all();
        return view('attribute_values.index', compact('attributeValues', 'productAttributes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_attribute_id' => 'required|exists:product_attributes,id',
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7', 
       ]);

        
            //  unique combination of attribute and value
            $exists = AttributeValue::where('product_attribute_id', $validatedData['product_attribute_id'])
                                    ->where('value', $validatedData['value'])
                                    ->exists();
            if ($exists) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'This attribute value already exists for the selected attribute.');
            }

            AttributeValue::create($validatedData);
            return redirect()->route('attribute_values.index')
                             ->with('success', 'Attribute value created successfully.');
    }


    public function edit(AttributeValue $attributeValue): View
    {
        $productAttributes = ProductAttribute::all(); 
        return view('attribute_values.edit', compact('attributeValue', 'productAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttributeValue $attributeValue): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_attribute_id' => 'required|exists:product_attributes,id',
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        
            // Check for unique combination of attribute and value, excluding the current record
            $exists = AttributeValue::where('product_attribute_id', $validatedData['product_attribute_id'])
                                    ->where('value', $validatedData['value'])
                                    ->where('id', '!=', $attributeValue->id)
                                    ->exists();
            if ($exists) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'This attribute value already exists for the selected attribute.');
            }

            $attributeValue->update($validatedData);
            return redirect()->route('attribute_values.index')
                             ->with('success', 'Attribute value updated successfully.');
    }

    public function destroy(AttributeValue $attributeValue): RedirectResponse
    {
        
            $attributeValue->delete();
            return redirect()->route('attribute_values.index')
                             ->with('success', 'Attribute value deleted successfully.');
    }
}
