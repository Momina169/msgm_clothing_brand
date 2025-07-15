<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Coupons') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <button type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal" data-bs-target="#addCouponModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Coupon
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

        <!-- Add Coupon Modal -->
        <div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info" id="addCouponModalLabel">Add New Coupon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="code" class="form-label">Coupon Code *</label>
                                <input type="text" name="code" id="code" class="form-control" required
                                    value="{{ old('code') }}">
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Discount Type *</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount
                                    </option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                        Percentage</option>
                                </select>
                             </div>

                            <div class="mb-3">
                                <label for="value" class="form-label">Discount Value *</label>
                                <input type="number" step="0.01" name="value" id="value" class="form-control"
                                    required min="0" value="{{ old('value') }}">
                             </div>

                            <div class="mb-3">
                                <label for="min_order_amount" class="form-label">Minimum Order Amount (optional)</label>
                                <input type="number" step="0.01" name="min_order_amount" id="min_order_amount"
                                    class="form-control" min="0" value="{{ old('min_order_amount') }}">
                             </div>

                            <div class="mb-3">
                                <label for="max_uses" class="form-label">Max Uses (optional)</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" min="1"
                                    value="{{ old('max_uses') }}">
                              </div>

                            <div class="mb-3">
                                <label for="max_uses_per_user" class="form-label">Max Uses Per User (optional)</label>
                                <input type="number" name="max_uses_per_user" id="max_uses_per_user"
                                    class="form-control" min="1" value="{{ old('max_uses_per_user') }}">
                              </div>

                            <div class="mb-3">
                                <label for="starts_at" class="form-label">Starts At (optional)</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
                                    value="{{ old('starts_at') }}">
                            </div>

                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expires At (optional)</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" class="form-control"
                                    value="{{ old('expires_at') }}">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Coupon</button>
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
                                <th scope="col">Code</th>
                                <th scope="col">Type</th>
                                <th scope="col">Value</th>
                                <th scope="col">Min Order</th>
                                <th scope="col">Uses</th>
                                <th scope="col">Starts At</th>
                                <th scope="col">Expires At</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ ucfirst($coupon->type) }}</td>
                                    <td>
                                        @if ($coupon->type == 'percentage')
                                            {{ number_format($coupon->value, 0) }}%
                                        @else
                                            PKR. {{ number_format($coupon->value, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $coupon->min_order_amount ? 'PKR. ' . number_format($coupon->min_order_amount, 2) : 'N/A' }}
                                    </td>
                                    <td>{{ $coupon->uses_count }} / {{ $coupon->max_uses ?? 'Unlimited' }}</td>
                                    <td>{{ $coupon->starts_at ? $coupon->starts_at->format('Y-m-d H:i') : 'N/A' }}
                                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($coupon->is_active && (!$coupon->expires_at || $coupon->expires_at->isFuture()))
                                            <span class="badge bg-success">Active</span>
                                        @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('coupons.edit', $coupon->id) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this coupon?');">
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
                                    <td colspan="9" class="text-center text-muted">No coupons found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
