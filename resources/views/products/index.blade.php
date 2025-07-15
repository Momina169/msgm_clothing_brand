<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button id="addproduct" type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#productModal">Add Product</button>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Product Modal -->
        <div class="modal fade" id="productModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-group" for="images">Product Image*</label>
                                <input class="form-control" type="file" name="images[0]" accept="image/*" 
                                multiple required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" required
                                    value="{{ old('price') }}">
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU (optional)</label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                            </div>

                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required min="0"
                                    value="{{ old('stock_quantity', 0) }}">
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category (optional)</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-check mb-3">
                                <div class="form-check mb-3">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create Product</button>
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

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">SKU</th>
                    <th scope="col">Category</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <th scope="row">{{ $product->id }}</th>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" width="120px" height="120px"
                                    alt="{{ $product->name }}" class="rounded">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>PKR. {{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->inventory ? $product->inventory->stock_level : 'N/A' }}</td>
                        <td> {{ $product->sku  ? $product->sku : 'N/A'}}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>
                            @if ($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa-regular fa-pen-to-square"></i></a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                class="d-inline">
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
                        <td colspan="7" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

</x-app-layout>
