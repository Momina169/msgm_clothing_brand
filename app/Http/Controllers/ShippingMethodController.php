<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::latest()->paginate(10);
        return view('shipping_methods.index', compact('shippingMethods'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods,name',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'is_active' => 'boolean', 
        ]);

        
            ShippingMethod::create($validatedData);
            return redirect()->route('shipping_methods.index')
                             ->with('success', 'Shipping method created successfully.');
    }

    
    public function edit(ShippingMethod $shippingMethod): View
    {
        return view('shipping_methods.edit', compact('shippingMethod'));
    }

   
    public function update(Request $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods,name,' . $shippingMethod->id,
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        
            $shippingMethod->update($validatedData);
            return redirect()->route('shipping_methods.index')
                             ->with('success', 'Shipping method updated successfully.');
       
    }


    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
            $shippingMethod->delete();
            return redirect()->route('shipping_methods.index')
                             ->with('success', 'Shipping method deleted successfully.');
    }
}
