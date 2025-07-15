<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h3>
    </x-slot>

    <div class="container">
        <button id="adduser" type="button" class="btn btn-outline-primary my-3" data-bs-toggle="modal"
            data-bs-target="#userModal">Add Category</button>

         @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Category Modal -->
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('categories.store') }}" >
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name *</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control"
                                    accept="image/*">
                            </div>


                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent Category (optional)</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">-- None (Top-level category) --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                            <button type="submit" class="btn btn-primary">Create Category</button>
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

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Parent</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <th scope="row">{{ $cat->id }}</th>
                        <td>
                            @if ($cat->image)
                                <img src="{{ asset($cat->image) }}" alt="category Image"
                                    class="w-16 h-16 object-cover rounded-md"
                                    style="max-width: 50px; max-height: 50px;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->slug }}</td>
                        <td>{{ $cat->description }}</td>
                        <td>
                            @if($cat->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $cat->parent?->name ?? '—' }}</td>
                        <td>
                         <a href="{{ 'edit/' . $cat->id }}"><i class="fa-regular fa-pen-to-square"></i></a>
                        <a href="{{ 'delete/' . $cat->id }}"><i class="fa-solid fa-trash text-danger mx-2"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
