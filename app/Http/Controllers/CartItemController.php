<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;    
use App\Models\Product;  
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cartItems = CartItem::with(['cart', 'product'])->latest()->paginate(10);

        $carts = Cart::all();
        $products = Product::all(); 

        return view('cart_items.index', compact('cartItems', 'carts', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id', // Changed from product_variant_id
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0', // Price at the time of adding to cart
        ]);

        CartItem::create($validatedData);

        return redirect()->route('cart_items.index')
                         ->with('success', 'Cart Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem): View
    {
        $cartItem->load(['cart', 'product']);
        return view('cart_items.show', compact('cartItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem): View
    {
        $carts = Cart::all();
        $products = Product::all(); // Changed from ProductVariant
        return view('cart_items.edit', compact('cartItem', 'carts', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $validatedData = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id', 
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cartItem->update($validatedData);

        return redirect()->route('cart_items.index')
                         ->with('success', 'Cart Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $cartItem->delete();

        return redirect()->route('cart_items.index')
                         ->with('success', 'Cart Item deleted successfully.');
    }
}
