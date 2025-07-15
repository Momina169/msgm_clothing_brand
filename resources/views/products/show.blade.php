<x-app-layout>
    <x-slot name="header">
         <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
             {{ __('Details: :productName', ['productName' => $product->name]) }}
        </h3>
    </x-slot>
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

    <div class="container py-4">

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white fs-5">
                        Product Images
                    </div>
                    <div class="card-body d-flex flex-wrap align-items-start justify-content-center gap-3 p-3">
                        @forelse($product->images as $image)

                            <div class="border rounded p-2 text-center d-flex flex-column align-items-center justify-content-center image-thumbnail"
                                style="width: 150px; height: 150px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#imageModal" data-full-src="{{ asset($image->file_name) }}"
                                data-alt="{{ $product->name }} ">

                                <img src="{{ asset($image->file_name) }}" alt="{{ $product->name }} Image"
                                    class="img-fluid rounded"
                                    style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                    
                                @if ($image->is_thumbnail)
                                    <span class="badge bg-info mt-1">Thumbnail</span>
                                @endif

                            </div>
                        @empty
                            <p class="text-muted text-center w-100">No images available.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white fs-5">
                        Product Information
                    </div>
                    <div class="card-body">

                        <h2 class="card-title fs-2 text-dark mb-1">{{ $product->name }}</h2>

                        <p class="card-subtitle text-muted mb-3"><small>SKU: {{ $product->sku ?? 'N/A' }}</small></p>

                        <hr>


                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Price:</strong></div>
                            <div class="col-md-8 fs-5 text-success">PKR. {{ number_format($product->price, 2) }}</div>
                        </div>

                         <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Main Product Stock:</strong></div>
                            <div class="col-md-8">{{ $product->stock_quantity ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Status:</strong></div>
                            <div class="col-md-8">
                                @if ($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Category:</strong></div>
                            <div class="col-md-8">{{ $product->category->name ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Current Stock:</strong></div>
                            <div class="col-md-8">{{ $product->inventory->stock_level ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Low Stock Threshold:</strong></div>
                            <div class="col-md-8">{{ $product->inventory->low_stock_threshold ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted"><strong>Description:</strong></div>
                            <div class="col-md-8">{{ $product->description ?? 'N/A' }}</div>
                        </div>

                        <hr class="my-4">

                        {{-- Product Variants Section --}}
                        <h5 class="fs-5 mb-3">Product Variants</h5>
                        @forelse($product->variants as $variant)
                            <div class="card card-body bg-light mb-2 shadow-sm">
                                <h6 class="mb-1 text-primary">{{ $variant->sku ?? 'Variant' }} -
                                    ${{ number_format($variant->price, 2) }}</h6>
                                <p class="mb-1"><small class="text-muted">Stock: {{ $variant->stock_level }}</small>
                                </p>
                                @if ($variant->image)
                                    <img src="{{ asset($variant->image) }}" alt="Variant Image"
                                        class="img-fluid rounded mb-2"
                                        style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                @endif
                                <ul class="list-unstyled mb-0">
                                    @foreach ($variant->attributeValues as $attributeValue)
                                        <li><strong>{{ $attributeValue->productAttribute->name ?? 'Attribute' }}:</strong>
                                            {{ $attributeValue->value }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-muted">No specific variants defined!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer Reviews --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fs-5">
                Customer Reviews ({{ $product->reviews->count() }})
            </div>
            <div class="card-body">
                @forelse($product->reviews as $review)
                    <div class="mb-3 pb-3 border-bottom">
                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                        <span class="text-warning ms-2">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @for ($i = $review->rating; $i < 5; $i++)
                                <i class="fa-regular fa-star"></i>
                            @endfor
                        </span>
                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet for this product.</p>
                @endforelse
            </div>
        </div>

        <div class="d-flex justify-content-start gap-2 mt-4">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                <i class="fa-regular fa-pen-to-square me-1"></i> Edit Product
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>

 <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            // Listen for when the modal is about to be shown
            imageModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const triggerElement = event.relatedTarget;

                // Extract info from data-bs-* attributes
                const fullSrc = triggerElement.getAttribute('data-full-src');
                const altText = triggerElement.getAttribute('data-alt');

                // Update the modal's content
                modalImage.src = fullSrc;
                modalImage.alt = altText;
                document.getElementById('imageModalLabel').textContent = altText;
            });
        });
    </script>
</x-app-layout>
