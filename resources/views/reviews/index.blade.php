<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Management') }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#addReviewModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Review
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

        <!-- Add Review Modal -->
        <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addReviewModalLabel">Add New Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product *</label>
                                <select name="product_id" id="product_id" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
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
                                <label for="rating" class="form-label">Rating *</label>
                                <select name="rating" id="rating" class="form-select" required>
                                    <option value="">-- Select Rating --</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment (optional)</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="hidden" name="is_approved" value="0">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="is_approved" value="1" checked>
                                <label class="form-check-label" for="is_approved">
                                    Approved
                                </label>
                                @error('is_approved')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Review</button>
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
                                <th scope="col">User</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Approved</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <th scope="row">{{ $review->id }}</th>
                                    <td>{{ $review->product->name ?? 'Product Not Found' }}</td>
                                    <td>{{ $review->user->name ?? 'User Not Found' }}</td>
                                    <td>
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @endfor
                                        @for ($i = $review->rating; $i < 5; $i++)
                                            <i class="fa-regular fa-star text-warning"></i>
                                        @endfor
                                    </td>
                                    <td>{{ Str::limit($review->comment, 50) ?? 'N/A' }}</td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('reviews.toggle-approval', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $review->is_approved ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                title="{{ $review->is_approved ? 'Disapprove' : 'Approve' }}">
                                                <i class="fa-solid {{ $review->is_approved ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No reviews found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
