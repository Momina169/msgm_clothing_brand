<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Top Headline') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <form action="{{ route('top_headlines.update', $topHeadline->id) }}" method="POST">
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

            <div class="mb-3">
                <label for="headline" class="form-label">Headline Text *</label>
                <input type="text" name="headline" id="headline" class="form-control" required
                    value="{{ old('headline', $topHeadline->Headline) }}" placeholder="e.g., Free Shipping on all orders!">
                @error('headline')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Headline</button>
            <a href="{{ route('top_headlines.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
