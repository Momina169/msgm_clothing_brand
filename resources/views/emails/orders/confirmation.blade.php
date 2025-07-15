@extends('layouts._layout')
@section('content')
<head>
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #f8f8f8; padding: 10px; text-align: center; border-bottom: 1px solid #eee; }
        .content { margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 0.9em; color: #777; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #eee; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Order Confirmation</h2>
        </div>
        <div class="content">
            <p>Dear {{ $order->user ? $order->user->name : ($order->guest_id ?? 'Customer') }},</p>
            <p>Thank you for your order! Your order number is: <strong>{{ $order->order_number }}</strong></p>

            <h3>Order Details:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total" style="text-align: right;">Total Amount:</td>
                        <td class="total">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <h3>Shipping Address:</h3>
            <p>
                {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}<br>
                {{ $order->shippingAddress->address_line_1 }}<br>
                @if($order->shippingAddress->address_line_2){{ $order->shippingAddress->address_line_2 }}<br>@endif
                {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip_code }}<br>
                {{ $order->shippingAddress->country }}<br>
                Phone: {{ $order->shippingAddress->phone_number ?? 'N/A' }}
            </p>

            <p>We will send you another email with tracking information once your order has shipped.</p>
            <p>If you have any questions, please contact us.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your E-commerce Store. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

@endsection