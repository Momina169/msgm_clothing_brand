<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::all();
        $inventoryItems = Inventory::with('product')->latest()->paginate(10);
        return view('inventories.index', compact('inventoryItems', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $inventories = new Inventory;
        $inventories->product_id = $request->product_id;
        $inventories->stock_level = $request->stock_level;
        $inventories->low_stock_threshold = $request->low_stock_threshold;
        $inventories->save();

        // update product's stock_quantity when inventory is created
        $inventories->product->update(['stock_quantity' => $inventories->stock_level]);

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventories): View
    {
        $inventories->load('product');
        return view('inventories.edit', compact('inventories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventories): RedirectResponse
    {
        $request->validate([
            'stock_level' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $inventories->update($request->all());

        // Update the product's stock_quantity after inventory is updated
        $inventories->product->update(['stock_quantity' => $inventories->stock_level]);

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventories): RedirectResponse
    {
        if ($inventories->product) {
            $inventories->product->update(['stock_quantity' => 0]);
        }

        $inventories->delete();

        return redirect()->route('inventories.index')
                         ->with('success', 'Inventory item deleted successfully.');
    }
}
