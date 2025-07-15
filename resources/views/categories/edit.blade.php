<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Category') }}
        </h3>
    </x-slot>

    <div class="container">
        <form  action="{{ url(route('categories.update')) }}">
            @csrf

            <div class="mb-3">
                        <input type="number" name="id" value="{{$category->id}}" hidden>

                <label class="form-label">Name</label>
                <input name="name" class="form-control" value="{{ $category->name }}" required>
            </div>

            <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                <label for="image" class="form-label">Variant Image (optional)</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset($category->image) }}" alt="Current category Image" class="img-thumbnail" style="max-width: 150px;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="clear_image" id="clear_image" value="1">
                            <label class="form-check-label" for="clear_image">Remove current image</label>
                        </div>
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input name="slug" class="form-control" value="{{ $category->slug }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">-- None --</option>
                    @foreach($otherCategories as $cat)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                       {{ $category->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <button type="submit" class="btn btn-success">Update Category</button>
        </form>
    </div>

</x-app-layout>
