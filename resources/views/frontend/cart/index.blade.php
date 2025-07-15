@extends('layouts._layout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

    @if(count($detailedCartItems) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th width="150">Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailedCartItems as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td><img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" width="80"></td>
                        <td>Rs. {{ number_format($item['price'], 2) }}</td>
                        <td>
                            <input type="number" class="form-control quantity-input"
                                   value="{{ $item['quantity'] }}"
                                   data-key="{{ $item['key'] }}"
                                   min="1">
                        </td>
                        <td class="item-subtotal">Rs. {{ number_format($item['subtotal'], 2) }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-key="{{ $item['key'] }}">
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>Rs. {{ number_format($cartTotal, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('checkout.show') }}" class="btn btn-success">Proceed to Checkout</a>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function () {
        const cartKey = this.dataset.cartKey;
        const quantity = this.value;

        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cart_key: cartKey,
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => location.reload());
    });
});

document.querySelectorAll('.remove-from-cart').forEach(button => {
    button.addEventListener('click', function () {
        const cartKey = this.dataset.cartKey;

        fetch("{{ route('cart.remove') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart_key: cartKey })
        })
        .then(res => res.json())
        .then(data => location.reload());
    });
});
</script>
@endpush
