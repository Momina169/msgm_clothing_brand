<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\OrderConfirmationMail; // Import your Mailable class

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function showCheckoutForm()
    {
        $cartItems = Session::get('cart', []);

        $subtotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));

        $totalAmount = $subtotal;

        // Corrected syntax: Auth::user() instead of Auth->user()
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        $shippingMethods = ShippingMethod::all();

        if (count($cartItems) === 0) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty. Please add items before checking out.');
        }

        return view('checkout', compact('cartItems', 'subtotal', 'totalAmount', 'addresses', 'shippingMethods'));
    }

    /**
     * Process the checkout submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processCheckout(Request $request)
    {
        $rules = [
            'shipping_id' => ['required', 'exists:shipping_methods,id'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'use_new_address' => ['required', 'boolean'],
        ];

        if (!Auth::check()) {
            $rules['guest_email'] = ['required', 'email', 'max:255'];
            $request->offsetUnset('user_id');
        } else {
            $request->offsetUnset('guest_email');
        }

        if ($request->boolean('use_new_address')) {
            $rules['address_id'] = ['nullable'];
            $rules['delivery_type'] = ['required', 'string', 'max:255'];
            $rules['delivery_first_name'] = ['required', 'string', 'max:255'];
            $rules['delivery_last_name'] = ['required', 'string', 'max:255'];
            $rules['delivery_company_name'] = ['nullable', 'string', 'max:255'];
            $rules['delivery_address_line_1'] = ['required', 'string', 'max:255'];
            $rules['delivery_address_line_2'] = ['nullable', 'string', 'max:255'];
            $rules['delivery_city'] = ['required', 'string', 'max:255'];
            $rules['delivery_state'] = ['nullable', 'string', 'max:255'];
            $rules['delivery_zip_code'] = ['required', 'string', 'max:20'];
            $rules['delivery_country'] = ['required', 'string', 'max:255'];
            $rules['delivery_phone_number'] = ['nullable', 'string', 'max:20'];
            $rules['delivery_is_default'] = ['boolean'];
        } else {
            $rules['address_id'] = ['required', 'exists:addresses,id'];
            $request->offsetUnset('delivery_type');
            $request->offsetUnset('delivery_first_name');
            $request->offsetUnset('delivery_last_name');
            $request->offsetUnset('delivery_company_name');
            $request->offsetUnset('delivery_address_line_1');
            $request->offsetUnset('delivery_address_line_2');
            $request->offsetUnset('delivery_city');
            $request->offsetUnset('delivery_state');
            $request->offsetUnset('delivery_zip_code');
            $request->offsetUnset('delivery_country');
            $request->offsetUnset('delivery_phone_number');
            $request->offsetUnset('delivery_is_default');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $addressId = null;

            if ($request->boolean('use_new_address')) {
                $newAddress = new Address();
                $newAddress->user_id = Auth::id();
                $newAddress->type = $request->input('delivery_type');
                $newAddress->first_name = $request->input('delivery_first_name');
                $newAddress->last_name = $request->input('delivery_last_name');
                $newAddress->company_name = $request->input('delivery_company_name');
                $newAddress->address_line_1 = $request->input('delivery_address_line_1');
                $newAddress->address_line_2 = $request->input('delivery_address_line_2');
                $newAddress->city = $request->input('delivery_city');
                $newAddress->state = $request->input('delivery_state');
                $newAddress->zip_code = $request->input('delivery_zip_code');
                $newAddress->country = $request->input('delivery_country');
                $newAddress->phone_number = $request->input('delivery_phone_number');
                $newAddress->is_default = $request->boolean('delivery_is_default');
                $newAddress->save();
                $addressId = $newAddress->id;
            } else {
                $addressId = $request->input('address_id');
            }

            $order = new Order();
            $order->order_number = 'ORD-' . Str::upper(Str::random(10)) . '-' . time();
            $order->total_amount = $request->input('total_amount');
            $order->status = 'pending';
            $order->payment_method = $request->input('payment_method');
            $order->payment_status = 'pending';
            $order->shipping_method_id = $request->input('shipping_id');
            $order->tracking_number = null;
            $order->notes = $request->input('notes');

            $order->shipping_address_id = $addressId;
            $order->billing_address_id = $addressId;

            if (Auth::check()) {
                $order->user_id = Auth::id();
                $order->guest_id = null;
            } else {
                $order->guest_id = $request->input('guest_email');
                $order->user_id = null;
            }

            $order->save();

            $cartItems = Session::get('cart', []);
            foreach ($cartItems as $item) {
                // Determine the correct product_id based on whether it's a variant or a base product
                $productId = $item['is_variant'] ? $item['product_id'] : $item['id'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId, // Use the determined product ID
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    // If you have product variants, you might store product_variant_id here too
                    // 'product_variant_id' => $item['is_variant'] ? $item['id'] : null,
                ]);
            }

            // Determine recipient email
            $recipientEmail = Auth::check() ? Auth::user()->email : $request->input('guest_email');

            // Send order confirmation email
            if ($recipientEmail) {
                Mail::to($recipientEmail)->send(new OrderConfirmationMail($order, $cartItems));
            }

            Session::forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully! Confirmation email sent.',
                'order_id' => $order->id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during order placement: ' . $e->getMessage()
            ], 500);
        }
    }
}

