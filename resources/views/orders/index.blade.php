<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <i class="fa-solid fa-plus me-1"></i> Add New Order
            </button>

            <!-- Filter by Status -->
            <form action="{{ route('orders.index') }}" method="GET" class="d-flex align-items-center">
                <label for="status_filter" class="form-label me-2 mb-0">Filter by Status:</label>
                <select name="status" id="status_filter" class="form-select w-auto me-2" onchange="this.form.submit()">
                    <option value="">All </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                    </option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
                <noscript><button type="submit" class="btn btn-secondary">Filter</button></noscript>
            </form>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Add Order Modal -->
        <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addOrderModalLabel">Add New Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">User (optional)</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">-- Select User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_id" class="form-label">Guest (optional)</label>
                                    <select name="guest_id" id="guest_id" class="form-select">
                                        <option value="">-- Select Guest --</option>
                                        @foreach ($guests as $guest)
                                            <option value="{{ $guest->id }}"
                                                {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                                Guest ID: {{ $guest->guest_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guest_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount *</label>
                                    <input type="number" step="0.01" name="total_amount" id="total_amount"
                                        class="form-control" required min="0" value="{{ old('total_amount') }}">
                                    @error('total_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Order Status *</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="processing"
                                            {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>
                                            Shipped</option>
                                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                        <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>
                                            Returned</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_address_id" class="form-label">Shipping Address *</label>
                                    <select name="shipping_address_id" id="shipping_address_id" class="form-select"
                                        required>
                                        <option value="">-- Select Shipping Address --</option>
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}"
                                                {{ old('shipping_address_id') == $address->id ? 'selected' : '' }}>
                                                {{ $address->first_name }} {{ $address->last_name }},
                                                {{ $address->address_line_1 }}, {{ $address->city }} (User:
                                                {{ $address->user->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipping_address_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="billing_address_id" class="form-label">Billing Address *</label>
                                    <select name="billing_address_id" id="billing_address_id" class="form-select"
                                        required>
                                        <option value="">-- Select Billing Address --</option>
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}"
                                                {{ old('billing_address_id') == $address->id ? 'selected' : '' }}>
                                                {{ $address->first_name }} {{ $address->last_name }},
                                                {{ $address->address_line_1 }}, {{ $address->city }} (User:
                                                {{ $address->user->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('billing_address_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method" class="form-label">Payment Method *</label>
                                    <input type="text" name="payment_method" id="payment_method"
                                        class="form-control" required value="{{ old('payment_method') }}"
                                        placeholder="e.g., Credit Card, PayPal">
                                    @error('payment_method')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_status" class="form-label">Payment Status *</label>
                                    <select name="payment_status" id="payment_status" class="form-select" required>
                                        <option value="pending"
                                            {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid"
                                            {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="refunded"
                                            {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded
                                        </option>
                                        <option value="failed"
                                            {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_method_id" class="form-label">Shipping Method
                                        (optional)</label>
                                    <select name="shipping_method_id" id="shipping_method_id" class="form-select">
                                        <option value="">-- Select Shipping Method --</option>
                                        @foreach ($shippingMethods as $method)
                                            <option value="{{ $method->id }}"
                                                {{ old('shipping_method_id') == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }} (${{ number_format($method->cost, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipping_method_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tracking_number" class="form-label">Tracking Number (optional)</label>
                                    <input type="text" name="tracking_number" id="tracking_number"
                                        class="form-control" value="{{ old('tracking_number') }}">
                                    @error('tracking_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (optional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Order #</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Shipping</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>{{ $order->order_number }}</td>
                                    <td>
                                        @if ($order->user)
                                            <i class="fa-solid fa-user me-1"></i> {{ $order->user->name }}
                                        @elseif($order->guest)
                                            <i class="fa-solid fa-user-secret me-1"></i> Guest
                                            ({{ Str::limit($order->guest->guest_id, 8) }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>PKR. {{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClass =
                                                [
                                                    'pending' => 'bg-warning',
                                                    'processing' => 'bg-info',
                                                    'shipped' => 'bg-primary',
                                                    'delivered' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    'returned' => 'bg-secondary',
                                                ][$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentStatusClass =
                                                [
                                                    'pending' => 'bg-warning',
                                                    'paid' => 'bg-success',
                                                    'refunded' => 'bg-info',
                                                    'failed' => 'bg-danger',
                                                ][$order->payment_status] ?? 'bg-secondary';
                                        @endphp
                                        <span
                                            class="badge {{ $paymentStatusClass }}">{{ ucfirst($order->payment_status) }}</span>
                                    </td>
                                    <td>
                                        {{ $order->shippingMethod->name ?? 'N/A' }}
                                        @if ($order->tracking_number)
                                            <br><small class="text-muted">#{{ $order->tracking_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-info btn-sm text-white" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
