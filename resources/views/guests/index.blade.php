<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guest Users') }}
        </h3>
    </x-slot>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <table class="col-8 table">
            <thead>
                <tr>

                    <th scope="col">ID</th>
                    <th scope="col">Guest ID</th>
                    <th scope="col">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $g)
                    <tr>
                        <th scope="row">{{ $g->id }}</th>
                        <td>{{ $g->guest_id }}</td>
                        <td>
                            <a href="{{ 'delete/' . $g->id }}"><i class="fa-solid fa-trash text-danger mx-2"></i></a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>
