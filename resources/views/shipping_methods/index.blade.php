<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shipping Methods') }}
        </h3>
    </x-slot>

    <div class="container">
        <button id="addShippingMethod" type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#userModal">Add Shipping Method</button>



        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Shipping Method Modal -->
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info">Add Shipping Method</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('shipping_methods.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Method Name *</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="cost" class="form-label">Cost *</label>
                                <input type="number" step="0.01" name="cost" id="cost" class="form-control"
                                    required min="0" value="{{ old('cost', 0.0) }}">
                            </div>

                            <div class="form-check mb-3">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Create Method</button>
                            <a href="{{ route('shipping_methods.index') }}" class="btn btn-secondary">Cancel</a>
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
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shippingMethods as $s)
                    <tr>
                        <th scope="row">{{ $s->id }}</th>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->description }}</td>
                        <td>{{ $s->cost }}</td>
                        <td>
                            @if ($s->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('shipping_methods.edit', $s->id) }}"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <form action="{{ route('shipping_methods.destroy', $s->id) }}" method="POST" class="d-inline">
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
