<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cart Item: :itemId', ['itemId' => $cartItem->id]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('cart_items.update', $cartItem->id) }}" method="POST">
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

            <div class="mb-3">
                <label for="cart_id" class="form-label">Cart *</label>
                <select name="cart_id" id="cart_id" class="form-select" required>
                    <option value="">-- Select Cart --</option>
                    @foreach ($carts as $cart)
                        <option value="{{ $cart->id }}" {{ old('cart_id', $cartItem->cart_id) == $cart->id ? 'selected' : '' }}>
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
                        <option value="{{ $product->id }}" {{ old('product_id', $cartItem->product_id) == $product->id ? 'selected' : '' }}>
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
                    value="{{ old('quantity', $cartItem->quantity) }}">
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price at Purchase *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required min="0"
                    value="{{ old('price', $cartItem->price) }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Cart Item</button>
            <a href="{{ route('cart_items.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
