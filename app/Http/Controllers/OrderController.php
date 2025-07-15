<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Guest;
use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str; 

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'guest', 'shippingAddress', 'billingAddress', 'shippingMethod']);

        //  filter by status
        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10);

        $users = User::all();
        $guests = Guest::all();
        $addresses = Address::all(); 
        $shippingMethods = ShippingMethod::all();

        return view('orders.index', compact('orders', 'users', 'guests', 'addresses', 'shippingMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,returned',
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|string|in:pending,paid,refunded,failed',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
            'tracking_number' => 'nullable|string|max:255|unique:orders,tracking_number',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Generate unique order no.
        $validatedData['order_number'] = 'ORD-' . Str::upper(Str::random(8)) . '-' . time();

        Order::create($validatedData);

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'guest', 'shippingAddress', 'billingAddress', 'shippingMethod', 'OrderItems.product']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): View
    {
        $users = User::all();
        $guests = Guest::all();
        $addresses = Address::all();
        $shippingMethods = ShippingMethod::all();
        return view('orders.edit', compact('order', 'users', 'guests', 'addresses', 'shippingMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,returned',
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|string|in:pending,paid,refunded,failed',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
            'tracking_number' => 'nullable|string|max:255|unique:orders,tracking_number,' . $order->id,
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update($validatedData);

        return redirect()->route('orders.index')
                         ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Order deleted successfully.');
    }
}
