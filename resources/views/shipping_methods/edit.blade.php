<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Shipping Method: :methodName', ['methodName' => $shippingMethod->name]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('shipping_methods.update', $shippingMethod->id) }}" method="POST">
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
                <label for="name" class="form-label">Method Name *</label>
                <input type="text" name="name" id="name" class="form-control" required
                    value="{{ old('name', $shippingMethod->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $shippingMethod->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cost" class="form-label">Cost *</label>
                <input type="number" step="0.01" name="cost" id="cost" class="form-control" required min="0"
                    value="{{ old('cost', $shippingMethod->cost) }}">
                @error('cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $shippingMethod->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Active
                </label>
                @error('is_active')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Shipping Method</button>
            <a href="{{ route('shipping_methods.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
