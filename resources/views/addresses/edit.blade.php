<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Address for: :userEmail', ['userEmail' => $address->user->email ?? 'N/A']) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('addresses.update', $address->id) }}" method="POST">
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
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <label for="user_id" class="form-label">User *</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Select User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $address->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <label for="type" class="form-label">Address Type *</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="shipping" {{ old('type', $address->type) == 'shipping' ? 'selected' : '' }}>
                            Shipping</option>
                        <option value="billing" {{ old('type', $address->type) == 'billing' ? 'selected' : '' }}>Billing
                        </option>
                        <option value="home" {{ old('type', $address->type) == 'home' ? 'selected' : '' }}>Home
                        </option>
                        <option value="work" {{ old('type', $address->type) == 'work' ? 'selected' : '' }}>Work
                        </option>
                        <option value="other" {{ old('type', $address->type) == 'other' ? 'selected' : '' }}>Other
                        </option>
                    </select>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                        value="{{ old('first_name', $address->first_name) }}">
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                        value="{{ old('last_name', $address->last_name) }}">
                    @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
            <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                <label for="address_line_1" class="form-label">Address Line 1 *</label>
                <input type="text" name="address_line_1" id="address_line_1" class="form-control" required
                    value="{{ old('address_line_1', $address->address_line_1) }}">
                @error('address_line_1')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                <label for="address_line_2" class="form-label">Address Line 2 (optional)</label>
                <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                    value="{{ old('address_line_2', $address->address_line_2) }}">
                @error('address_line_2')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City *</label>
                    <input type="text" name="city" id="city" class="form-control" required
                        value="{{ old('city', $address->city) }}">
                    @error('city')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State/Province</label>
                    <input type="text" name="state" id="state" class="form-control"
                        value="{{ old('state', $address->state) }}">
                    @error('state')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="zip_code" class="form-label">Zip Code *</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" required
                        value="{{ old('zip_code', $address->zip_code) }}">
                    @error('zip_code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country *</label>
                    <input type="text" name="country" id="country" class="form-control" required
                        value="{{ old('country', $address->country) }}">
                    @error('country')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
            <div class="mb-3 col-md-6 col-sm-12">
                <label for="phone_number" class="form-label">Phone Number (optional)</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number', $address->phone_number) }}">
                @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-md-6 col-sm-12">
                <label for="company_name" class="form-label">Company Name (optional)</label>
                <input type="text" name="company_name" id="company_name" class="form-control"
                    value="{{ old('company_name', $address->company_name) }}">
                @error('company_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <div class="form-check mb-3">
                <input type="hidden" name="is_default" value="0">
                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1"
                    {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_default">
                    Set as Default Address
                </label>
                @error('is_default')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Address</button>
            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
