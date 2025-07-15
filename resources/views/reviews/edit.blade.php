<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Review for: :productName', ['productName' => $review->product->name ?? 'N/A']) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('reviews.update', $review->id) }}" method="POST">
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

            <div class="mb-3">
                <label for="product_id" class="form-label">Product *</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $review->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="user_id" class="form-label">User *</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $review->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="rating" class="form-label">Rating *</label>
                <select name="rating" id="rating" class="form-select" required>
                    <option value="">-- Select Rating --</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                    @endfor
                </select>
                @error('rating')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Comment (optional)</label>
                <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment', $review->comment) }}</textarea>
                @error('comment')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="hidden" name="is_approved" value="0">
                <input class="form-check-input" type="checkbox" name="is_approved" id="is_approved" value="1"
                    {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_approved">
                    Approved
                </label>
                @error('is_approved')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Review</button>
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
