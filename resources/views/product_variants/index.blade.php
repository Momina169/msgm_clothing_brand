<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Variants Management') }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addProductVariantModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Product Variant
        </button>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Add Product Variant Modal -->
        <div class="modal fade" id="addProductVariantModal" tabindex="-1" aria-labelledby="addProductVariantModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addProductVariantModalLabel">Add Product Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('product_variants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Associated Product *</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label">Variant SKU (optional)</label>
                                <input type="text" name="sku" id="sku" class="form-control"
                                    value="{{ old('sku') }}" placeholder="e.g., TSHIRT-RED-L">
                                @error('sku')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control"
                                    required min="0" value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock_level" class="form-label">Stock Level *</label>
                                <input type="number" name="stock_level" id="stock_level" class="form-control" required
                                    min="0" value="{{ old('stock_level', 0) }}">
                                @error('stock_level')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Variant Image (optional)</label>
                                <input type="file" name="image" id="image" class="form-control"
                                    accept="image/*">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="fs-5 mt-4 mb-3">Define Variant Attributes *</h5>
                            <div class="row">
                                @forelse($productAttributes as $attribute)
                                    <div class="col-md-6 mb-3">
                                        <label for="attribute_value_{{ $attribute->id }}"
                                            class="form-label">{{ $attribute->name }}</label>
                                        <select name="attribute_values[]" id="attribute_value_{{ $attribute->id }}"
                                            class="form-select" required>
                                            <option value="">-- Select {{ $attribute->name }} --</option>
                                            @foreach ($attribute->attributeValues as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ in_array($value->id, old('attribute_values', [])) ? 'selected' : '' }}>
                                                    {{ $value->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('attribute_values')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @empty
                                    <p class="text-muted">No product attributes defined. Please add attributes first.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Variant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
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
                                <th scope="col">Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Price</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Attributes</th>
                                <th scope="col">Last Updated</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productVariants as $variant)
                                <tr>
                                    <th scope="row">{{ $variant->id }}</th>
                                    <td>
                                        @if ($variant->image)
                                            <img src="{{ asset($variant->image) }}" alt="Variant Image"
                                                class="w-16 h-16 object-cover rounded-md"
                                                style="max-width: 50px; max-height: 50px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $variant->product->name ?? 'Product Not Found' }}</td>
                                    <td>{{ $variant->sku ?? 'N/A' }}</td>
                                    <td>PKR. {{ number_format($variant->price, 2) }}</td>
                                    <td>
                                        @if ($variant->stock_level == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @else
                                            <span class="badge bg-success">{{ $variant->stock_level }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse($variant->attributeValues as $attrValue)
                                            <span
                                                class="badge bg-info text-dark">{{ $attrValue->productAttribute->name ?? 'N/A' }}:
                                                {{ $attrValue->value }}</span><br>
                                        @empty
                                            <span class="text-muted">No Attributes</span>
                                        @endforelse
                                    </td>
                                    <td>{{ $variant->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('product_variants.show', $variant->id) }}"
                                            class="btn btn-info btn-sm text-white" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('product_variants.edit', $variant->id) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('product_variants.destroy', $variant->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product variant?');">
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
                                    <td colspan="9" class="text-center text-muted">No product variants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $productVariants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
