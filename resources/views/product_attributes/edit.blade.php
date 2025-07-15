<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product Attribute: :attributeName', ['attributeName' => $productAttribute->name]) }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <h1 class="fs-1 text-primary mb-4">Edit Product Attribute</h1>

        <form action="{{ route('product_attributes.update', $productAttribute->id) }}" method="POST">
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
                <label for="name" class="form-label">Attribute Name *</label>
                <input type="text" name="name" id="name" class="form-control" required
                    value="{{ old('name', $productAttribute->name) }}" placeholder="e.g., Color, Size, Material">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Attribute Type *</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="text" {{ old('type', $productAttribute->type) == 'text' ? 'selected' : '' }}>Text (e.g., Small, Large)</option>
                    <option value="color" {{ old('type', $productAttribute->type) == 'color' ? 'selected' : '' }}>Color (e.g., Red, Blue)</option>
                    <option value="number" {{ old('type', $productAttribute->type) == 'number' ? 'selected' : '' }}>Number (e.g., 256GB, 512GB)</option>
                    <option value="dropdown" {{ old('type', $productAttribute->type) == 'dropdown' ? 'selected' : '' }}>Dropdown (General selection)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update Attribute</button>
            <a href="{{ route('product_attributes.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
