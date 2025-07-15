<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Attribute Value: :value', ['value' => $attributeValue->value]) }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <h1 class="fs-1 text-primary mb-4">Edit Attribute Value</h1>

        <form action="{{ route('attribute_values.update', $attributeValue->id) }}" method="POST">
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
                <label for="product_attribute_id" class="form-label">Product Attribute *</label>
                <select name="product_attribute_id" id="product_attribute_id" class="form-select" required>
                    <option value="">-- Select Attribute --</option>
                    @foreach ($productAttributes as $attribute)
                        <option value="{{ $attribute->id }}"
                            {{ old('product_attribute_id', $attributeValue->product_attribute_id) == $attribute->id ? 'selected' : '' }}>
                            {{ $attribute->name }}
                        </option>
                    @endforeach
                </select>
             </div>

            <div class="mb-3">
                <label for="value" class="form-label">Value *</label>
                <input type="text" name="value" id="value" class="form-control" required
                    value="{{ old('value', $attributeValue->value) }}" placeholder="e.g., Red, Small, Cotton">
            </div>

            <div class="mb-3">
                <label for="color_code" class="form-label">Color Code (for Color attribute)</label>
                <input type="color" name="color_code" id="color_code" class="form-control form-control-color"
                    value="{{ old('color_code', $attributeValue->color_code ?? '#FFFFFF') }}">
                <small class="form-text text-muted">Select a color if this is a color attribute value.</small>
             </div>

            <button type="submit" class="btn btn-success">Update Value</button>
            <a href="{{ route('attribute_values.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
