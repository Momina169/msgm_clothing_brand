<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Order: :orderNumber', ['orderNumber' => $order->order_number]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="user_id" class="form-label">User (optional)</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">-- Select User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="guest_id" class="form-label">Guest (optional)</label>
                    <select name="guest_id" id="guest_id" class="form-select">
                        <option value="">-- Select Guest --</option>
                        @foreach ($guests as $guest)
                            <option value="{{ $guest->id }}"
                                {{ old('guest_id', $order->guest_id) == $guest->id ? 'selected' : '' }}>
                                Guest ID: {{ $guest->guest_id }}
                            </option>
                        @endforeach
                    </select>
                    @error('guest_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="total_amount" class="form-label">Total Amount *</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control"
                        required min="0" value="{{ old('total_amount', $order->total_amount) }}">
                    @error('total_amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Order Status *</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="processing"
                            {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>
                            Processing</option>
                        <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>
                            Shipped
                        </option>
                        <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>
                            Delivered</option>
                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                        <option value="returned" {{ old('status', $order->status) == 'returned' ? 'selected' : '' }}>
                            Returned</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4 mb-3">
                    <label for="shipping_address_id" class="form-label">Shipping Address *</label>
                    <select name="shipping_address_id" id="shipping_address_id" class="form-select" required>
                        <option value="">-- Select Shipping Address --</option>
                        @foreach ($addresses as $address)
                            <option value="{{ $address->id }}"
                                {{ old('shipping_address_id', $order->shipping_address_id) == $address->id ? 'selected' : '' }}>
                                {{ $address->first_name }} {{ $address->last_name }}, {{ $address->address_line_1 }},
                                {{ $address->city }} (User: {{ $address->user->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('shipping_address_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="billing_address_id" class="form-label">Billing Address *</label>
                    <select name="billing_address_id" id="billing_address_id" class="form-select" required>
                        <option value="">-- Select Billing Address --</option>
                        @foreach ($addresses as $address)
                            <option value="{{ $address->id }}"
                                {{ old('billing_address_id', $order->billing_address_id) == $address->id ? 'selected' : '' }}>
                                {{ $address->first_name }} {{ $address->last_name }}, {{ $address->address_line_1 }},
                                {{ $address->city }} (User: {{ $address->user->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('billing_address_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="payment_method" class="form-label">Payment Method *</label>
                    <input type="text" name="payment_method" id="payment_method" class="form-control" required
                        value="{{ old('payment_method', $order->payment_method) }}"
                        placeholder="e.g., Credit Card, PayPal">
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="payment_status" class="form-label">Payment Status *</label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending"
                            {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="paid"
                            {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>
                            Paid</option>
                        <option value="refunded"
                            {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>
                            Refunded
                        </option>
                        <option value="failed"
                            {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed
                        </option>
                    </select>
                    @error('payment_status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="shipping_method_id" class="form-label">Shipping Method (optional)</label>
                    <select name="shipping_method_id" id="shipping_method_id" class="form-select">
                        <option value="">-- Select Shipping Method --</option>
                        @foreach ($shippingMethods as $method)
                            <option value="{{ $method->id }}"
                                {{ old('shipping_method_id', $order->shipping_method_id) == $method->id ? 'selected' : '' }}>
                                {{ $method->name }} (${{ number_format($method->cost, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('shipping_method_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="tracking_number" class="form-label">Tracking Number (optional)</label>
                    <input type="text" name="tracking_number" id="tracking_number" class="form-control"
                        value="{{ old('tracking_number', $order->tracking_number) }}">
                    @error('tracking_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            

            <div class="col-md-4 mb-3">
                <label for="notes" class="form-label">Notes (optional)</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $order->notes) }}</textarea>
                @error('notes')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
</div>
            <button type="submit" class="btn btn-success">Update Order</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
