<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sale Details: :saleId', ['saleId' => $sale->id]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Sale Information
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Sale ID:</strong></div>
                    <div class="col-md-8">{{ $sale->id }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Product:</strong></div>
                    <div class="col-md-8">
                        @if($sale->product)
                            <a href="{{ route('products.show', $sale->product->id) }}">
                                {{ $sale->product->name }} (SKU: {{ $sale->product->sku ?? 'N/A' }})
                            </a>
                        @else
                            Product Not Found
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Quantity:</strong></div>
                    <div class="col-md-8">{{ $sale->quantity }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Price at Sale:</strong></div>
                    <div class="col-md-8">${{ number_format($sale->price_at_sale, 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Total Amount:</strong></div>
                    <div class="col-md-8">${{ number_format($sale->total_amount, 2) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Customer:</strong></div>
                    <div class="col-md-8">
                        @if($sale->user)
                            <a href="{{ url(route('users', ['user' => $sale->user->id])) }}"> {{ $sale->user->name ?? 'N/A' }} ({{ $sale->user->email ?? 'N/A' }})</a>
                        @elseif($sale->guest)
                            Guest: {{ $sale->guest->guest_id ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Sale Date:</strong></div>
                    <div class="col-md-8">{{ $sale->sale_date?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                </div>
                @if($sale->notes)
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Notes:</strong></div>
                    <div class="col-md-8">{{ $sale->notes }}</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Created At:</strong></div>
                    <div class="col-md-8">{{ $sale->created_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted"><strong>Last Updated:</strong></div>
                    <div class="col-md-8">{{ $sale->updated_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start gap-2 mt-4">
            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning">
                <i class="fa-regular fa-pen-to-square me-1"></i> Edit Sale
            </a>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Sales
            </a>
        </div>
    </div>
</x-app-layout>
