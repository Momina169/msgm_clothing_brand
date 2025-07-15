<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Sale Record: :saleId', ['saleId' => $sale->id]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
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
                <label for="product_id" class="form-label">Product *</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }}) - Current Price: ${{ number_format($product->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity *</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required min="1"
                        value="{{ old('quantity', $sale->quantity) }}">
                    @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price_at_sale" class="form-label">Price at Sale *</label>
                    <input type="number" step="0.01" name="price_at_sale" id="price_at_sale" class="form-control" required min="0"
                        value="{{ old('price_at_sale', $sale->price_at_sale) }}">
                    @error('price_at_sale')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">User (optional)</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">-- Select User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $sale->user_id) == $user->id ? 'selected' : '' }}>
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
                            <option value="{{ $guest->id }}" {{ old('guest_id', $sale->guest_id) == $guest->id ? 'selected' : '' }}>
                                Guest ID: {{ $guest->guest_id }}
                            </option>
                        @endforeach
                    </select>
                    @error('guest_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="sale_date" class="form-label">Sale Date (optional)</label>
                <input type="datetime-local" name="sale_date" id="sale_date" class="form-control"
                    value="{{ old('sale_date', $sale->sale_date->format('Y-m-d\TH:i')) }}">
                @error('sale_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes (optional)</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $sale->notes) }}</textarea>
                @error('notes')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Sale</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
