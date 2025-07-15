<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
         {{ __('Edit: :productName', ['productName' => $product->name]) }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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

            <div class= "row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <label for="name" class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" required
                    value="{{  $product->name}}">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control"
                    value="{{  $product->slug }}">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <label for="sku" class="form-label">SKU (optional)</label>
                <input type="text" name="sku" class="form-control"
                    value="{{  $product->sku }}">
            </div>
            </div>
            <div class=" mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
            
            </div>
            {{-- Existing Images Display --}}
            <div class="mb-3">
                <label class="form-label">Current Images:</label>
                <div class="d-flex flex-wrap gap-3">
                
                   @forelse($product->images as $image)
                        <div class="position-relative border rounded p-2 text-center d-flex flex-column align-items-center justify-content-center">

                            <img src="{{ asset($image->file_name) }}" alt="{{ $product->name }} Image"
                                 class="img-fluid rounded image-thumbnail"
                                 style="max-width: 120px; max-height: 120px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal" data-bs-target="#imageModal"
                                 data-full-src="{{ asset($image->file_name) }}"
                                 data-alt="{{ $product->name }} Image">

                            @if ($image->is_thumbnail)
                                <span class="badge bg-info mt-1">Thumbnail</span>
                            @endif

                            {{-- Checkbox for deletion --}}
                            <div class="form-check position-absolute top-0 end-0 ">
                                <input class="form-check-input" type="checkbox" 
                                       name="existing_images_to_delete[]" value="{{ $image->id }}"
                                       id="delete_image_{{ $image->id }}"
                                       {{ in_array($image->id, old('existing_images_to_delete', [])) ? 'checked' : '' }}>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center w-100">No images available.</p>
                    @endforelse
                </div>
                <small class="form-text text-muted">Check the box on an image to mark it for deletion, then click Update Product.</small>

            </div>

            {{-- New Images Upload --}}
             <div class="row">
            <div class="col-lg-4 mb-3">
                <label for="images" class="form-label">Upload New Images (optional)</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                <small class="form-text text-muted">Select multiple files to upload.</small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <label for="price" class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" class="form-control" required
                    value="{{  $product->price }}">
            </div>


            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <label for="category_id" class="form-label">Category (optional)</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{  $product->category_id== $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="is_active" value="0"> {{-- Hidden input for unchecked checkbox --}}
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                    {{  $product->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Active
                </label>
            </div>

            <button type="submit" class="btn btn-success">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
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
