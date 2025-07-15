<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Addresses Management') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addAddressModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Address
        </button>

        <!-- Add Address Modal -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addAddressModalLabel">Add New Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
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
                                <label for="user_id" class="form-label">User *</label>
                                <select name="user_id" id="user_id" class="form-select" required>
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
                                <label for="type" class="form-label">Address Type *</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                                    <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Home</option>
                                    <option value="work" {{ old('type') == 'work' ? 'selected' : '' }}>Work</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name (optional)</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address_line_1" class="form-label">Address Line 1 *</label>
                                <input type="text" name="address_line_1" id="address_line_1" class="form-control" required value="{{ old('address_line_1') }}">
                                @error('address_line_1')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address_line_2" class="form-label">Address Line 2 (optional)</label>
                                <input type="text" name="address_line_2" id="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
                                @error('address_line_2')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" name="city" id="city" class="form-control" required value="{{ old('city') }}">
                                    @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}">
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="zip_code" class="form-label">Zip Code *</label>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control" required value="{{ old('zip_code') }}">
                                    @error('zip_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <input type="text" name="country" id="country" class="form-control" required value="{{ old('country') }}">
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number (optional)</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="hidden" name="is_default" value="0">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    Set as Default Address
                                </label>
                                @error('is_default')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Address</button>
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
                                <th scope="col">User</th>
                                <th scope="col">Type</th>
                                <th scope="col">Address</th>
                                <th scope="col">City, State, Zip</th>
                                <th scope="col">Country</th>
                                <th scope="col">Default</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($addresses as $address)
                                <tr>
                                    <th scope="row">{{ $address->id }}</th>
                                    <td>{{ $address->user->name ?? 'N/A' }} ({{ $address->user->email ?? 'N/A' }})</td>
                                    <td>{{ ucfirst($address->type) }}</td>
                                    <td>
                                        {{ $address->address_line_1 }}<br>
                                        @if($address->address_line_2){{ $address->address_line_2 }}<br>@endif
                                        @if($address->company_name) <em>({{ $address->company_name }})</em><br>@endif
                                        @if($address->first_name || $address->last_name) {{ $address->first_name }} {{ $address->last_name }}<br>@endif
                                        @if($address->phone_number) Phone: {{ $address->phone_number }} @endif
                                    </td>
                                    <td>
                                        {{ $address->city }}@if($address->state), {{ $address->state }}@endif<br>
                                        {{ $address->zip_code }}
                                    </td>
                                    <td>{{ $address->country }}</td>
                                    <td>
                                        @if($address->is_default)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this address?');">
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
                                    <td colspan="8" class="text-center text-muted">No addresses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $addresses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
