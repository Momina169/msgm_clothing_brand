<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cart: :cartId', ['cartId' => $cart->id]) }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <h1 class="fs-1 text-primary mb-4">Edit Cart</h1>

        <form action="{{ route('carts.update', $cart->id) }}" method="POST">
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
            <div class="mb-3 col-lg-4">
                <label for="user_id" class="form-label">User (optional)</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $cart->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-4">
                <label for="guest_id" class="form-label">Guest (optional)</label>
                <select name="guest_id" id="guest_id" class="form-select">
                    <option value="">-- Select Guest --</option>
                    @foreach ($guests as $guest)
                        <option value="{{ $guest->id }}" {{ old('guest_id', $cart->guest_id) == $guest->id ? 'selected' : '' }}>
                            Guest ID: {{ $guest->guest_id }}
                        </option>
                    @endforeach
                </select>
                @error('guest_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-lg-4">
                <label for="status" class="form-label">Status *</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="active" {{ old('status', $cart->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status', $cart->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="abandoned" {{ old('status', $cart->status) == 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <button type="submit" class="btn btn-success">Update Cart</button>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
