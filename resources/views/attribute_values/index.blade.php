<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attribute Values Management') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addAttributeValueModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Attribute Value
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

        <!-- Add Attribute Value Modal -->
        <div class="modal fade" id="addAttributeValueModal" tabindex="-1" aria-labelledby="addAttributeValueModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addAttributeValueModalLabel">Add Attribute Value</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('attribute_values.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="product_attribute_id" class="form-label">Product Attribute *</label>
                                <select name="product_attribute_id" id="product_attribute_id" class="form-select"
                                    required>
                                    <option value="">-- Select Attribute --</option>
                                    @foreach ($productAttributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                            {{ old('product_attribute_id') == $attribute->id ? 'selected' : '' }}>
                                            {{ $attribute->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="value" class="form-label">Value *</label>
                                <input type="text" name="value" id="value" class="form-control" required
                                    value="{{ old('value') }}" placeholder="e.g., Red, Small, Cotton">
                            </div>

                            <div class="mb-3">
                                <label for="color_code" class="form-label">Color Code (for Color attribute)</label>
                                <input type="color" name="color_code" id="color_code"
                                    class="form-control form-control-color" value="{{ old('color_code', '#FFFFFF') }}">
                                <small class="form-text text-muted">Select a color if this is a color attribute
                                    value.</small>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Value</button>
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
                                <th scope="col">Attribute Name</th>
                                <th scope="col">Value</th>
                                <th scope="col">Color Preview</th>
                                <th scope="col">Last Updated</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attributeValues as $value)
                                <tr>
                                    <th scope="row">{{ $value->id }}</th>
                                    <td>{{ $value->productAttribute->name ?? 'Attribute Not Found' }}</td>
                                    <td>{{ $value->value }}</td>
                                    <td>
                                        @if ($value->color_code)
                                            <div
                                                style="width: 30px; height: 30px; background-color: {{ $value->color_code }};
                                                 border: 1px solid #ccc; border-radius: 5px;">
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $value->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('attribute_values.edit', $value->id) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('attribute_values.destroy', $value->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this attribute value?');">
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
                                    <td colspan="6" class="text-center text-muted">No attribute values found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $attributeValues->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
