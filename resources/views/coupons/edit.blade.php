<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Coupon: :couponCode', ['couponCode' => $coupon->code]) }}
        </h3>
    </x-slot>

    <div class="container py-4">
        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
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
            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="code" class="form-label">Coupon Code *</label>
                <input type="text" name="code" id="code" class="form-control" required
                    value="{{ old('code', $coupon->code) }}">
               </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="type" class="form-label">Discount Type *</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
           </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="value" class="form-label">Discount Value *</label>
                <input type="number" step="0.01" name="value" id="value" class="form-control" required min="0"
                    value="{{ old('value', $coupon->value) }}">
            </div>
            </div>

            <div class="row">
            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="min_order_amount" class="form-label">Minimum Order Amount (optional)</label>
                <input type="number" step="0.01" name="min_order_amount" id="min_order_amount" class="form-control" min="0"
                    value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
           </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="max_uses" class="form-label">Max Uses (optional)</label>
                <input type="number" name="max_uses" id="max_uses" class="form-control" min="1"
                    value="{{ old('max_uses', $coupon->max_uses) }}">
             </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="max_uses_per_user" class="form-label">Max Uses Per User (optional)</label>
                <input type="number" name="max_uses_per_user" id="max_uses_per_user" class="form-control" min="1"
                    value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}">
            </div>
            </div>

            <div class="row">
            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="starts_at" class="form-label">Starts At (optional)</label>
                <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
                    value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
             </div>

            <div class="mb-3 col-lg-4 col-md-6 col-sm-12">
                <label for="expires_at" class="form-label">Expires At (optional)</label>
                <input type="datetime-local" name="expires_at" id="expires_at" class="form-control"
                    value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="form-check mb-3 col-lg-4 col-md-6 col-sm-12">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Active
                </label>
            </div>
            </div>
            <button type="submit" class="btn btn-success">Update Coupon</button>
            <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
