<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Order Item: :itemId', ['itemId' => $orderItem->id]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('order_items.update', $orderItem->id) }}" method="POST">
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
                <label for="order_id" class="form-label">Order *</label>
                <select name="order_id" id="order_id" class="form-select" required>
                    <option value="">-- Select Order --</option>
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id', $orderItem->order_id) == $order->id ? 'selected' : '' }}>
                            {{ $order->order_number }} (Total: ${{ number_format($order->total_amount, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('order_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">Product *</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $orderItem->product_id) == $product->id ? 'selected' : '' }}>
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
                    value="{{ old('quantity', $orderItem->quantity) }}">
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price at Purchase *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required min="0"
                    value="{{ old('price', $orderItem->price) }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Order Item</button>
            <a href="{{ route('order_items.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
