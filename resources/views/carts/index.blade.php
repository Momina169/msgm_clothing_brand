<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cart') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addCartModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Cart
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

        <!-- Add Cart Modal -->
        <div class="modal fade" id="addCartModal" tabindex="-1" aria-labelledby="addCartModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addCartModalLabel">Add New Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('carts.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                    
                            <div class="mb-3">
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

                            <div class="mb-3">
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

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="abandoned" {{ old('status') == 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Cart</button>
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
                                <th scope="col">Customer</th>
                                <th scope="col">Status</th>
                                <th scope="col">Items</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carts as $cart)
                                <tr>
                                    <th scope="row">{{ $cart->id }}</th>
                                    <td>
                                        @if($cart->user)
                                            <i class="fa-solid fa-user me-1"></i> {{ $cart->user->name }}
                                        @elseif($cart->guest)
                                            <i class="fa-solid fa-user-secret me-1"></i> Guest ({{ Str::limit($cart->guest->guest_id, 8) }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'active' => 'bg-success',
                                                'completed' => 'bg-primary',
                                                'abandoned' => 'bg-danger',
                                            ][$cart->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($cart->status) }}</span>
                                    </td>
                                    <td>{{ $cart->cartItems->sum('quantity') }}</td>
                                    <td>PKR. {{ number_format($cart->total_amount, 2) }}</td>
                                    <td>{{ $cart->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-info btn-sm text-white" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('carts.destroy', $cart->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this cart?');">
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
                                    <td colspan="7" class="text-center text-muted">No carts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $carts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
