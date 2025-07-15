<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sales') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addSaleModal">
            <i class="fa-solid fa-plus me-1"></i> Record New Sale
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

        <!-- Add Sale Modal -->
        <div class="modal fade" id="addSaleModal" tabindex="-1" aria-labelledby="addSaleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addSaleModalLabel">Record New Sale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                           
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product *</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }}) - Current Price: ${{ number_format($product->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Quantity *</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required min="1"
                                        value="{{ old('quantity') }}">
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price_at_sale" class="form-label">Price at Sale *</label>
                                    <input type="number" step="0.01" name="price_at_sale" id="price_at_sale" class="form-control" required min="0"
                                        value="{{ old('price_at_sale') }}">
                                    @error('price_at_sale')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">User (optional)</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">-- Select User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guest_id" class="form-label">Guest (optional)</label>
                                    <select name="guest_id" id="guest_id" class="form-select">
                                        <option value="">-- Select Guest --</option>
                                        @foreach ($guests as $guest)
                                            <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                                Guest ID: {{ $guest->guest_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guest_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="sale_date" class="form-label">Sale Date (optional)</label>
                                <input type="datetime-local" name="sale_date" id="sale_date" class="form-control"
                                    value="{{ old('sale_date', now()->format('Y-m-d\TH:i')) }}">
                                @error('sale_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (optional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Record Sale</button>
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
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price at Sale</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Sale Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <th scope="row">{{ $sale->id }}</th>
                                    <td>{{ $sale->product->name ?? 'Product Not Found' }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>PKR. {{ number_format($sale->price_at_sale, 2) }}</td>
                                    <td>PKR. {{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @if($sale->user)
                                            <i class="fa-solid fa-user me-1"></i> {{ $sale->user->name }}
                                        @elseif($sale->guest)
                                            <i class="fa-solid fa-user-secret me-1"></i> Guest ({{ Str::limit($sale->guest->guest_id, 8) }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm text-white" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this sale record?');">
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
                                    <td colspan="8" class="text-center text-muted">No sales records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
