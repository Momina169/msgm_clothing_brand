<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cart Details: :cartId', ['cartId' => $cart->id]) }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Cart Information
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Cart ID:</strong></div>
                    <div class="col-md-8">{{ $cart->id }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Customer:</strong></div>
                    <div class="col-md-8">
                        @if($cart->user)
                            <a href="{{ url(route('users', ['user' => $cart->user->id])) }}"> {{ $cart->user->name ?? 'N/A' }} ({{ $cart->user->email ?? 'N/A' }})</a>
                        @elseif($cart->guest)
                            Guest: {{ $cart->guest->guest_id ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        @php
                            $statusClass = [
                                'active' => 'bg-success',
                                'completed' => 'bg-primary',
                                'abandoned' => 'bg-danger',
                            ][$cart->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }} fs-6">{{ ucfirst($cart->status) }}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Total Items:</strong></div>
                    <div class="col-md-8">{{ $cart->cartItems->sum('quantity') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Total Amount:</strong></div>
                    <div class="col-md-8">PKR. {{ number_format($cart->total_amount, 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Created At:</strong></div>
                    <div class="col-md-8">{{ $cart->created_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Last Updated:</strong></div>
                    <div class="col-md-8">{{ $cart->updated_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Cart Items ({{ $cart->cartItems->count() }})
            </div>
            <div class="card-body">
                <!-- Add Item to Cart Form -->
                <form action="{{ route('carts.addItem', $cart->id) }}" method="POST" class="mb-4 p-3 border rounded bg-light">
                    @csrf
                    <h6 class="fs-5 text-info mb-3">Add New Item to Cart</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_variant_id" class="form-label">Product Variant *</label>
                            <select name="product_variant_id" id="product_variant_id" class="form-select" required>
                                <option value="">-- Select Variant --</option>
                                @foreach (App\Models\ProductVariant::with('product')->get() as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->product->name ?? 'N/A' }} - SKU: {{ $variant->sku ?? 'N/A' }} (Price: ${{ number_format($variant->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_variant_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required min="1" value="1">
                            @error('quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-plus"></i> Add</button>
                        </div>
                    </div>
                </form>

                @forelse($cart->cartItems as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        @if($item->productVariant?->product?->image)
                            <img src="{{ asset($item->productVariant->product->image) }}" alt="{{ $item->productVariant->product->name ?? 'Product Image' }}" class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="me-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 1px solid #ddd;">
                                <i class="fa-solid fa-image text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $item->productVariant->product->name ?? 'Product Not Found' }}</strong>
                            <br>
                            <small class="text-muted">
                                Variant SKU: {{ $item->productVariant->sku ?? 'N/A' }} |
                                Quantity: {{ $item->quantity ?? 'N/A' }} |
                                Price: ${{ number_format($item->price ?? 0, 2) }} |
                                Subtotal: ${{ number_format(($item->quantity ?? 0) * ($item->price ?? 0), 2) }}
                            </small>
                            <br>
                            @if($item->productVariant)
                                <a href="{{ route('product_variants.show', $item->productVariant->id) }}" class="btn btn-sm btn-outline-info mt-1">View Variant</a>
                            @endif
                            <form action="{{ route('carts.removeItem', ['cart' => $cart->id, 'item' => $item->id]) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger mt-1" onclick="return confirm('Are you sure you want to remove this item from the cart?');">
                                    <i class="fa-solid fa-trash-can"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No items in this cart.</p>
                @endforelse
            </div>
        </div>


        <div class="d-flex justify-content-start gap-2 mt-4">
            <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning">
                <i class="fa-regular fa-pen-to-square me-1"></i> Edit Cart
            </a>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Carts
            </a>
        </div>
    </div>
</x-app-layout>
