@extends('layouts._layout')

@section('content')
    <div class="container py-5">
        {{-- Breadcrumbs --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                @if ($product->category)
                    <li class="breadcrumb-item"><a
                            href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row product-details-card p-4">
            {{-- Left Column: Product Images --}}
            <div class="col-md-6 mb-4">
                <div class="main-image-container mb-3 text-center">
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" class="product-image-main" alt="{{ $product->name }}"
                            id="mainProductImage">
                    @else
                        <img src="https://placehold.co/600x400/E0E0E0/333333?text={{ urlencode($product->name) }}"
                            class="product-image-main" alt="{{ $product->name }}" id="mainProductImage">
                    @endif
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-center">

                    {{-- Display additional product images as thumbnails --}}
                    @forelse($product->images as $image)
                        @if ($image->file_name !== $product->image)
                            {{-- Avoid duplicating the main image thumbnail --}}
                            <img src="{{ asset($image->file_name) }}" class="product-thumbnail"
                                alt="{{ $product->name }} Image" data-full-src="{{ asset($image->file_name) }}">
                        @endif
                    @empty
                        {{-- No additional images --}}
                    @endforelse
                </div>
            </div>

            {{-- Right Column: Product info --}}
            <div class="col-md-6 mb-4">
                <h1 class="fs-3 text-dark mb-2">{{ $product->name }}</h1>
                <p class="text-muted mb-3"><small> {{ $product->sku ?? 'N/A' }}</small></p>

                <div class="price-display fs-5 mb-3">Rs. {{ number_format($product->price, 2) }}</div>

                <ul class="text-decoration: none; list-style-type: none">
                    <li><strong> By Pcs:</strong> 3pc</li>
                    <li><strong> Shirt:</strong> Lawn</li>
                    <li><strong> Duppata:</strong> Embroided Lawn</li>
                    <li><strong> Trouser:</strong> Lawn</li>
                    <ul>



                        <div class="mb-3">
                            <strong>Category:</strong>
                            @if ($product->category)
                                <a href="{{ route('categories.show', $product->category->slug) }}"
                                    class="text-decoration-none text-primary">{{ $product->category->name }}</a>
                            @else
                                N/A
                            @endif
                        </div>

                        <div class="mb-4">
                            @if ($product->stock_quantity <= ($product->inventory->low_stock_threshold ?? 0) && $product->stock_quantity > 0)
                                <span class="stock-status low-stock">Low Stock ({{ $product->stock_quantity }} in
                                    stock)</span>
                            @elseif($product->stock_quantity == 0)
                                <span class="stock-status out-of-stock">Out of Stock</span>
                            @else
                                <span class="stock-status in-stock">In Stock ({{ $product->stock_quantity }}
                                    available)</span>
                            @endif
                        </div>

                        <hr class="my-4">
                        <p class="mb-4">{{ $product->description ?? 'No description available.' }}</p>

                        {{-- Add to Cart form --}}
                        <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="quantity" class="form-label me-3 mb-0">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1"
                                    min="1" max="{{ $product->stock_quantity }}" style="width: 100px;">
                            </div>


                            @if ($product->stock_quantity > 0)
                                <button type="submit" class="btn btn-dark btn-lg" id="addToCartButton">
                                    <i class="fa-solid fa-cart-plus me-2"></i> Add to Cart
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg mt-3" disabled>Out of Stock</button>
                            @endif
                        </form>
            </div>
        </div>

        <hr class="my-5">

        {{-- Customer Reviews Section --}}
        <div class="row">
            <div class="col-12">
                <h3 class="fs-3 mb-4">Customer Reviews ({{ $product->reviews->where('is_approved', true)->count() }})</h3>
                @forelse($product->reviews->where('is_approved', true) as $review)
                    <div class="review-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $review->user->name ?? 'Anonymous User' }}</h6>
                            <div class="text-warning">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                                @for ($i = $review->rating; $i < 5; $i++)
                                    <i class="fa-regular fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">Reviewed on {{ $review->created_at->format('M d, Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted">No approved reviews yet for this product. Be the first to review!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" class="img-fluid" id="modalImage" alt="Enlarged Product Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Cart Confirmation Modal -->
    <div class="modal fade add-to-cart-modal" id="addToCartConfirmationModal" tabindex="-1"
        aria-labelledby="addToCartConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToCartConfirmationModalLabel">
                        <i class="fa-solid fa-circle-check"></i>
                        Item Added to Cart!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your selected item has been successfully added to your shopping cart.</p>
                    <div class="product-summary">
                        <img id="modalAddedProductImage" src="{{ asset($product->images) }}" alt="Product Image"
                            class="img-fluid"
                            onerror="this.onerror=null;this.src='https://placehold.co/80x80/cccccc/000000?text=Error';">
                        <div class="product-details">
                            <p class="product-name" id="modalAddedProductName">Product Name</p>
                            <p class="product-qty" id="modalAddedProductQty">Quantity: 1</p>
                            <p class="product-price" id="modalAddedProductPrice">$0.00</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Continue Shopping</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary" id="viewCartLink">View Cart (0
                        Items)</a>
                    <a href="{{ route('cart.index') }}" class="btn btn-success" id="viewCheckoutLink">Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles for product details page */
        .product-details-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        }

        .main-image-container {
            max-width: 100%;
            height: auto;
            overflow: hidden;
            border-radius: 0.75rem;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image-main {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .product-image-main:hover {
            transform: scale(1.02);
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s ease, transform 0.2s ease;
        }

        .product-thumbnail:hover,
        .product-thumbnail.active {
            border-color: #0d6efd;
            /* Bootstrap primary blue */
            transform: translateY(-2px);
        }

        .price-display {
            font-weight: 700;
            color: #0d6efd;
            /* Bootstrap primary blue */
        }



        .stock-status {
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 0.5rem;
            display: inline-block;
            font-size: 0.85rem;
        }

        .in-stock {
            background-color: #d4edda;
            color: #155724;
        }

        .low-stock {
            background-color: #fff3cd;
            color: #856404;
        }

        .out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }

        .review-card {
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        /* Styles for Add to Cart Confirmation Modal */
        .add-to-cart-modal .modal-content {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .add-to-cart-modal .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            background-color: #ffffff;
        }

        .add-to-cart-modal .modal-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #343a40;
            display: flex;
            align-items: center;
        }

        .add-to-cart-modal .modal-title .fa-circle-check {
            color: #28a745;
            margin-right: 0.75rem;
            font-size: 1.8rem;
        }

        .add-to-cart-modal .btn-close {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .add-to-cart-modal .modal-body {
            padding: 1.5rem;
            color: #495057;
            font-size: 1.05rem;
        }

        .add-to-cart-modal .product-summary {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .add-to-cart-modal .product-summary img {
            width: 80px;
            height: 80px;
            border-radius: 0.5rem;
            object-fit: cover;
            margin-right: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-modal .product-summary .product-details p {
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .add-to-cart-modal .product-summary .product-details .product-name {
            font-weight: 600;
            color: #212529;
            font-size: 1.1rem;
        }

        .add-to-cart-modal .product-summary .product-details .product-qty {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .add-to-cart-modal .product-summary .product-details .product-price {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1.2rem;
        }

        .add-to-cart-modal .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        @media (min-width: 576px) {
            .add-to-cart-modal .modal-footer {
                flex-direction: row;
                justify-content: flex-end;
                gap: 1rem;
            }
        }

        .add-to-cart-modal .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .add-to-cart-modal .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .add-to-cart-modal .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .add-to-cart-modal .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .add-to-cart-modal .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded: Initializing product page scripts.');

            const mainProductImage = document.getElementById('mainProductImage');
            const thumbnails = document.querySelectorAll('.product-thumbnail');
            const quantityInput = document.getElementById('quantity');
            const addToCartButton = document.getElementById('addToCartButton');


            // --- Image Gallery Logic ---
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    console.log('Thumbnail clicked:', this.dataset.fullSrc);
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    mainProductImage.src = this.dataset.fullSrc;
                });
            });

            // --- Image Modal Logic ---
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImage = document.getElementById('modalImage');

            mainProductImage.addEventListener('click', function() {
                console.log('Main product image clicked for modal.');
                modalImage.src = this.src;
                modalImage.alt = this.alt;
                document.getElementById('imageModalLabel').textContent = this.alt;
                imageModal.show();
            });


            // --- Add to Cart Confirmation Modal elements and Bootstrap instance ---
            const modalAddedProductName = document.getElementById('modalAddedProductName');
            const modalAddedProductQty = document.getElementById('modalAddedProductQty');
            const modalAddedProductPrice = document.getElementById('modalAddedProductPrice');
            const modalAddedProductImage = document.getElementById('modalAddedProductImage');
            const viewCartLink = document.getElementById('viewCartLink');
            const addToCartConfirmationModal = new bootstrap.Modal(document.getElementById(
                'addToCartConfirmationModal'));

            // --- AJAX for Add to Cart Form Submission ---
            const addToCartForm = document.getElementById('addToCartForm');
            console.log('Add to Cart Form element:', addToCartForm);

            if (addToCartForm) {
                addToCartForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    console.log("Prevented default form submission. Starting AJAX request...");

                    addToCartButton.disabled = true;
                    addToCartButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

                    const formData = new FormData(this);

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message ||
                                `HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();
                        console.log('Add to cart successful:', result);

                        const itemAdded = result.itemAdded;
                        if (itemAdded) {
                            modalAddedProductName.textContent = itemAdded.name;
                            modalAddedProductQty.textContent = `Quantity: ${itemAdded.quantity}`;
                            modalAddedProductPrice.textContent = `RS. ${itemAdded.price.toFixed(2)}`;
                            modalAddedProductImage.src = itemAdded.image ||
                                'https://placehold.co/80x80/cccccc/000000?text=No+Image';
                            modalAddedProductImage.alt = itemAdded.name;
                        } else {
                            modalAddedProductName.textContent = '{{ $product->name }}';
                            modalAddedProductQty.textContent = `Quantity: ${quantityInput.value}`;
                            modalAddedProductPrice.textContent =
                                `RS. {{ number_format($product->price, 2) }}`;
                            modalAddedProductImage.src = '{{ asset($product->image) }}' ||
                                'https://placehold.co/80x80/cccccc/000000?text=Product';
                            modalAddedProductImage.alt = '{{ $product->name }}';
                        }

                        viewCartLink.textContent = `View Cart (${result.cartCount || 'Many'} Items)`;
                        viewCartLink.href = '{{ route('cart.index') }}';

                        addToCartConfirmationModal.show();
                        console.log('Add to Cart Confirmation Modal should be visible.');

                    } catch (error) {
                        console.error('Error adding to cart:', error);
                        alert('Error adding to cart: ' + error.message);
                    } finally {
                        addToCartButton.disabled = false;
                        addToCartButton.innerHTML =
                            '<i class="fa-solid fa-cart-plus me-2"></i> Add to Cart';
                    }
                });
            } else {
                console.error("Error: 'addToCartForm' element not found. Check your HTML ID.");
            }


        });
    </script>
@endsection
