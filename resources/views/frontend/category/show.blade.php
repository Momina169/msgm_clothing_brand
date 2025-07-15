@extends('layouts._layout')
@section('content')

 <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @if($category->parent)
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $category->parent->slug) }}">{{ $category->parent->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="text-center mb-5 fs-1 heading-font text-dark">{{ $category->name }} Products</h1>

        @if($products->isEmpty())
            <p class="text-center text-muted fs-4">No products found in this category yet.</p>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($products as $product)
                    <div class="col">
                        <div class="product-card card">
                            <a href="{{ route('product.details', $product->slug) }}">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <img src="https://placehold.co/400x200/E0E0E0/333333?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                                @endif
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">PKR. {{ number_format($product->price, 2) }}</p>
                                <a href="{{ route('product.details', $product->slug) }}" class="btn btn-dark">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @endif
    </div>


@endsection