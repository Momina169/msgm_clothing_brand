<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Guest;
use App\Models\Product;
use App\Models\ProductVariant; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session; 

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $carts = Cart::with(['user', 'guest', 'cartItems.productVariant'])
                     ->latest()
                     ->paginate(10);

        $users = User::all();
        $guests = Guest::all();
        $productVariants = ProductVariant::all(); 

        return view('carts.index', compact('carts', 'users', 'guests', 'productVariants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'status' => 'required|string|in:active,completed,abandoned',
        ]);

        if (empty($validatedData['user_id']) && empty($validatedData['guest_id'])) {
            return redirect()->back()->withInput()->with('error', 'Either a User or a Guest must be selected.');
        }

        Cart::create($validatedData);

        return redirect()->route('carts.index')
                         ->with('success', 'Cart created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart): View
    {
        $cart->load(['user', 'guest', 'cartItems.productVariant.product']); 
        return view('carts.show', compact('cart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart): View
    {
        $users = User::all();
        $guests = Guest::all();
        return view('carts.edit', compact('cart', 'users', 'guests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'status' => 'required|string|in:active,completed,abandoned',
        ]);

        if (empty($validatedData['user_id']) && empty($validatedData['guest_id'])) {
            return redirect()->back()->withInput()->with('error', 'Either a User or a Guest must be selected.');
        }

        $cart->update($validatedData);

        return redirect()->route('carts.index')
                         ->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();

        return redirect()->route('carts.index')
                         ->with('success', 'Cart deleted successfully.');
    }

            
   public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $productVariantId = $request->input('product_variant_id'); 

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $itemToAdd = null;

        if ($productVariantId) {
            
            $variant = $product->variants()->find($productVariantId); 
            if ($variant) {
                $itemToAdd = [
                    'id' => $variant->id, 
                    'is_variant' => true,
                    'product_id' => $product->id, 
                    'name' => $product->name . ' (' . ($variant->sku ?? 'N/A') . ')',
                    'price' => (float) $variant->price, 
                    'image' => $product->image, 
                    'quantity' => $quantity
                ];
            }
        } else {
           
            if ($product) {
                $itemToAdd = [
                    'id' => $product->id,
                    'is_variant' => false,
                    'name' => $product->name,
                    'price' => (float) $product->price, 
                    'image' => $product->image,
                    'quantity' => $quantity
                ];
            }
        }

        if (!$itemToAdd) {
            return response()->json(['message' => 'Product or variant not found'], 404);
        }

        $cart = Session::get('cart', []);

        $cartKey = $itemToAdd['is_variant'] ? 'variant_' . $itemToAdd['id'] : 'product_' . $itemToAdd['id'];

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = $itemToAdd;
        }

        Session::put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Product added to cart!',
                'cartCount' => $cartCount,
                'itemAdded' => $itemToAdd
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

public function updateCart(Request $request)
{
    $cartKey = $request->input('cart_key');
    $quantity = (int) $request->input('quantity');

    $cart = Session::get('cart', []);

    if (!isset($cart[$cartKey])) {
        return response()->json(['message' => 'Item not found in cart'], 404);
    }

    if ($quantity <= 0) {
        unset($cart[$cartKey]);
        Session::put('cart', $cart);
    } else {
        $cart[$cartKey]['quantity'] = $quantity;
        Session::put('cart', $cart);
    }

    // Recalculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $itemSubtotal = isset($cart[$cartKey])
        ? $cart[$cartKey]['price'] * $cart[$cartKey]['quantity']
        : 0;

    return response()->json([
        'cartKey' => $cartKey,
        'removed' => !isset($cart[$cartKey]),
        'itemSubtotal' => $itemSubtotal,
        'cartTotal' => $total,
    ]);
}



    /**
     * Remove a product from the cart.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
    {
        $cartKey = $request->input('cart_key');

        $cart = Session::get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put('cart', $cart);
            $cartCount = array_sum(array_column($cart, 'quantity'));
            return response()->json(['message' => 'Product removed from cart!', 'cartCount' => $cartCount]);
        }

        return response()->json(['message' => 'Product not found in cart'], 404);
    }

     public function showCart()
{
    $cartItems = Session::get('cart', []);
    $detailedCartItems = [];

    foreach ($cartItems as $key => $item) {
        if ($item['is_variant']) {
            $variant = ProductVariant::with('product')->find($item['id']);
            if ($variant) {
                $detailedCartItems[$key] = [
                    'key' => $key,
                    'name' => $variant->product->name . ' (' . ($variant->sku ?? 'N/A') . ')',
                    'image' => $variant->product->image,
                    'price' => $variant->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $variant->price * $item['quantity'],
                ];
            }
        } else {
            $product = Product::find($item['id']);
            if ($product) {
                $detailedCartItems[$key] = [
                    'key' => $key,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
            }
        }
    }

    $cartTotal = array_sum(array_column($detailedCartItems, 'subtotal'));

    return view('frontend.cart.index', compact('detailedCartItems', 'cartTotal'));
}

}