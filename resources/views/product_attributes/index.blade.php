<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Attributes Management') }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addProductAttributeModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Product Attribute
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

        <!-- Add Product Attribute Modal -->
        <div class="modal fade" id="addProductAttributeModal" tabindex="-1" aria-labelledby="addProductAttributeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addProductAttributeModalLabel">Add Product Attribute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('product_attributes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="name" class="form-label">Attribute Name *</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="{{ old('name') }}" placeholder="e.g., Color, Size, Material">
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Attribute Type *</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text (e.g., Small, Large)</option>
                                    <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>Color (e.g., Red, Blue)</option>
                                    <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Number (e.g., 256GB, 512GB)</option>
                                    <option value="dropdown" {{ old('type') == 'dropdown' ? 'selected' : '' }}>Dropdown (General selection)</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Attribute</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
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
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Last Updated</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productAttributes as $attribute)
                                <tr>
                                    <th scope="row">{{ $attribute->id }}</th>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ ucfirst($attribute->type) }}</td>
                                    <td>{{ $attribute->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('product_attributes.edit', $attribute->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('product_attributes.destroy', $attribute->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product attribute? This will also delete all associated attribute values.');">
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
                                    <td colspan="5" class="text-center text-muted">No product attributes found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $productAttributes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
