<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product Variant: :variantSku', ['variantSku' => $productVariant->sku ?? 'N/A']) }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <form action="{{ route('product_variants.update', $productVariant->id) }}" method="POST" enctype="multipart/form-data">
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

            <div class="row">
            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="product_id" class="form-label">Associated Product *</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $productVariant->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="sku" class="form-label">Variant SKU (optional)</label>
                <input type="text" name="sku" id="sku" class="form-control"
                    value="{{ old('sku', $productVariant->sku) }}" placeholder="e.g., TSHIRT-RED-L">
                @error('sku')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="price" class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required min="0"
                    value="{{ old('price', $productVariant->price) }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <div class="row">
            <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                <label for="stock_level" class="form-label">Stock Level *</label>
                <input type="number" name="stock_level" id="stock_level" class="form-control" required min="0"
                    value="{{ old('stock_level', $productVariant->stock_level) }}">
                @error('stock_level')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                <label for="image" class="form-label">Variant Image (optional)</label>
                @if($productVariant->image)
                    <div class="mb-2">
                        <img src="{{ asset($productVariant->image) }}" alt="Current Variant Image" class="img-thumbnail" style="max-width: 150px;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="clear_image" id="clear_image" value="1">
                            <label class="form-check-label" for="clear_image">Remove current image</label>
                        </div>
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <h5 class="fs-5 mt-4 mb-3">Define Variant Attributes *</h5>
            <div class="row">
                @forelse($productAttributes as $attribute)
                    <div class="col-md-6 mb-3">
                        <label for="attribute_value_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
                        <select name="attribute_values[]" id="attribute_value_{{ $attribute->id }}" class="form-select" required>
                            <option value="">-- Select {{ $attribute->name }} --</option>
                            @foreach($attribute->attributeValues as $value)
                                <option value="{{ $value->id }}"
                                    {{ in_array($value->id, old('attribute_values', $productVariant->attributeValues->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $value->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('attribute_values')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @empty
                    <p class="text-muted">No product attributes defined. Please add attributes first.</p>
                @endforelse
            </div>

            <button type="submit" class="btn btn-success">Update Variant</button>
            <a href="{{ route('product_variants.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
