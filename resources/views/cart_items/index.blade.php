<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cart Items') }}
        </h3>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addCartItemModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Cart Item
        </button>

        <!-- Add Cart Item Modal -->
        <div class="modal fade" id="addCartItemModal" tabindex="-1" aria-labelledby="addCartItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addCartItemModalLabel">Add New Cart Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('cart_items.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cart_id" class="form-label">Cart *</label>
                                <select name="cart_id" id="cart_id" class="form-select" required>
                                    <option value="">-- Select Cart --</option>
                                    @foreach ($carts as $cart)
                                        <option value="{{ $cart->id }}" {{ old('cart_id') == $cart->id ? 'selected' : '' }}>
                                            Cart ID: {{ $cart->id }} (User: {{ $cart->user->name ?? 'Guest' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('cart_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product *</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required min="1"
                                    value="{{ old('quantity') }}">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price at Purchase *</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" required min="0"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Cart Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
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
                                <th scope="col">Cart ID</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cartItems as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->cart->id ?? 'N/A' }}</td>
                                    <td>{{ $item->product->name ?? 'Product Not Found' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>PKR. {{ number_format($item->price, 2) }}</td>
                                    <td>PKR. {{ number_format($item->quantity * $item->price, 2) }}</td>
                                    <td>
                                        <a href="{{ route('cart_items.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('cart_items.destroy', $item->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this cart item?');">
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
                                    <td colspan="7" class="text-center text-muted">No cart items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $cartItems->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
