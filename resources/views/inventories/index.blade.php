<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventory') }}
        </h3>
    </x-slot>

    <div class="container">
        <button id="addInventory" type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#userModal">Add Inventory Item</button>



        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Inventory Modal -->
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info">Add Inventory Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('inventories.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product *</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select a Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="stock_level" class="form-label">Stock Level *</label>
                                <input type="number" name="stock_level" id="stock_level" class="form-control" required
                                    min="0" value="{{ old('stock_level') }}">
                            </div>

                            <div class="mb-3">
                                <label for="low_stock_threshold" class="form-label">Low Stock Threshold *</label>
                                <input type="number" name="low_stock_threshold" id="low_stock_threshold"
                                    class="form-control" required min="0"
                                    value="{{ old('low_stock_threshold') }}">
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create Inventory</button>
                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Cancel</a>
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
                    <th scope="col">Product</th>
                    <th scope="col">Stock Level</th>
                    <th scope="col">Low Stock Threshold</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventoryItems as $in)
                    <tr>
                        <th scope="row">{{ $in->id }}</th>
                        <td>{{ $in->product_id }}</td>
                        <td>{{ $in->stock_level }}</td>
                        <td>{{ $in->low_stock_threshold }}</td>
                        <td>
                            <a href="{{ route('inventories.edit', $in->id) }}"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <form action="{{ route('inventories.destroy', $in->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0"
                                    onclick="return confirm('Are you sure you want to delete this inventory item?');">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
