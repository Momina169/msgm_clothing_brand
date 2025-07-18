<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __( 'Roles/Users') }}
        </h3>
    </x-slot>

    <div class="container py-4">


        <button id="adduser" type="button" class="btn btn-outline-primary my-1 " data-bs-toggle="modal"
            data-bs-target="#userModal">Add Role/User</button>
        <!-- Add user Modal -->
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-3 text-info">Add Role/User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ url(route('storeuser')) }}">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                                <p class="text-danger"> {{$errors->first('email')}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <select name="usertype" id="usertype">
                                  
                                    <option value="admin" > admin</option>
                                    <option value="user">user</option>
                                </select>
                            </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <table class="table">
            <thead>
                <tr>

                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Usertype</th>
                    <th scope="col">Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->usertype }}</td>
                    <td>
                        <a href="{{ 'edit/' . $user->id }}"><i class="fa-regular fa-pen-to-square"></i></a>
                        <a href="{{ 'delete/' . $user->id }}"><i class="fa-solid fa-trash text-danger mx-2"></i></a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>