<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;    
use App\Models\Product;  
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orderItems = OrderItem::with(['order', 'product'])->latest()->paginate(10);

        $orders = Order::all();
        $products = Product::all();

        return view('order_items.index', compact('orderItems', 'orders', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0', // Price at the time of order
        ]);

        OrderItem::create($validatedData);

        return redirect()->route('order_items.index')
                         ->with('success', 'Order Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem): View
    {
        $orderItem->load(['order', 'product']);
        return view('order_items.show', compact('orderItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem): View
    {
        $orders = Order::all();
        $products = Product::all();
        return view('order_items.edit', compact('orderItem', 'orders', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem): RedirectResponse
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem->update($validatedData);

        return redirect()->route('order_items.index')
                         ->with('success', 'Order Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem): RedirectResponse
    {
        $orderItem->delete();

        return redirect()->route('order_items.index')
                         ->with('success', 'Order Item deleted successfully.');
    }
}
