<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details: :orderNumber', ['orderNumber' => $order->order_number]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Order Information
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Order ID:</strong></div>
                    <div class="col-md-8">{{ $order->id }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Order Number:</strong></div>
                    <div class="col-md-8">{{ $order->order_number }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Customer:</strong></div>
                    <div class="col-md-8">
                        @if($order->user)
                            <a href="{{ url(route('users', ['user' => $order->user->id])) }}"> {{ $order->user->name }} ({{ $order->user->email }})</a>
                        @elseif($order->guest)
                            Guest: {{ $order->guest->guest_id }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Total Amount:</strong></div>
                    <div class="col-md-8">PKR. {{ number_format($order->total_amount, 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Order Status:</strong></div>
                    <div class="col-md-8">
                        @php
                            $statusClass = [
                                'pending' => 'bg-warning',
                                'processing' => 'bg-info',
                                'shipped' => 'bg-primary',
                                'delivered' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                'returned' => 'bg-secondary',
                            ][$order->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }} fs-6">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Payment Method:</strong></div>
                    <div class="col-md-8">{{ $order->payment_method }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Payment Status:</strong></div>
                    <div class="col-md-8">
                        @php
                            $paymentStatusClass = [
                                'pending' => 'bg-warning',
                                'paid' => 'bg-success',
                                'refunded' => 'bg-info',
                                'failed' => 'bg-danger',
                            ][$order->payment_status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $paymentStatusClass }} fs-6">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Shipping Method:</strong></div>
                    <div class="col-md-8">{{ $order->shippingMethod->name ?? 'N/A' }}</div>
                </div>
                @if($order->tracking_number)
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Tracking Number:</strong></div>
                    <div class="col-md-8">{{ $order->tracking_number }}</div>
                </div>
                @endif
                @if($order->notes)
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Notes:</strong></div>
                    <div class="col-md-8">{{ $order->notes }}</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Order Date:</strong></div>
                    <div class="col-md-8">{{ $order->created_at->format('Y-m-d H:i:s') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Last Updated:</strong></div>
                    <div class="col-md-8">{{ $order->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white fs-5">
                        Shipping Address
                    </div>
                    <div class="card-body">
                        @if($order->shippingAddress)
                            <p>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                            @if($order->shippingAddress->company_name)<p>{{ $order->shippingAddress->company_name }}</p>@endif
                            <p>{{ $order->shippingAddress->address_line_1 }}</p>
                            @if($order->shippingAddress->address_line_2)<p>{{ $order->shippingAddress->address_line_2 }}</p>@endif
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip_code }}</p>
                            <p>{{ $order->shippingAddress->country }}</p>
                            @if($order->shippingAddress->phone_number)<p>Phone: {{ $order->shippingAddress->phone_number }}</p>@endif
                        @else
                            <p class="text-muted">Shipping address not available.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white fs-5">
                        Billing Address
                    </div>
                    <div class="card-body">
                        @if($order->billingAddress)
                            <p>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</p>
                            @if($order->billingAddress->company_name)<p>{{ $order->billingAddress->company_name }}</p>@endif
                            <p>{{ $order->billingAddress->address_line_1 }}</p>
                            @if($order->billingAddress->address_line_2)<p>{{ $order->billingAddress->address_line_2 }}</p>@endif
                            <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zip_code }}</p>
                            <p>{{ $order->billingAddress->country }}</p>
                            @if($order->billingAddress->phone_number)<p>Phone: {{ $order->billingAddress->phone_number }}</p>@endif
                        @else
                            <p class="text-muted">Billing address not available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Order Items ({{ $order->orderItems->count() }})
            </div>
            <div class="card-body">
                @forelse($order->orderItems as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="me-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 1px solid #ddd;">
                                <i class="fa-solid fa-image text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $item->product->name ?? 'Product Not Found' }}</strong>
                            <br>
                            <small class="text-muted">Quantity: {{ $item->quantity }} | Price: PKR. {{ number_format($item->price, 2) }}</small>
                            @if($item->product)
                                <br><a href="{{ route('products.show', $item->product->id) }}" class="btn btn-sm btn-outline-info mt-1">View Product</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No items in this order.</p>
                @endforelse
            </div>
        </div>


        <div class="d-flex justify-content-start gap-2 mt-4">
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">
                <i class="fa-regular fa-pen-to-square me-1"></i> Edit Order
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>
</x-app-layout>
