<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Inventory for: :productName', ['productName' => $inventory->product->name ?? 'N/A']) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
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
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" id="product_name" class="form-control" value="{{ $inventory->product->name ?? 'Product Not Found' }}" disabled>
                <small class="form-text text-muted">Product cannot be changed once inventory is created.</small>
            </div>

            <div class="mb-3">
                <label for="stock_level" class="form-label">Stock Level *</label>
                <input type="number" name="stock_level" id="stock_level" class="form-control" required min="0"
                    value="{{ old('stock_level', $inventory->stock_level) }}">
                @error('stock_level')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="low_stock_threshold" class="form-label">Low Stock Threshold *</label>
                <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-control" required min="0"
                    value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}">
                @error('low_stock_threshold')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Inventory</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
