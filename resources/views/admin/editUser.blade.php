
<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __( 'Edit User') }}
        </h3>
    </x-slot>

    <form action="{{ url(route('updateUser')) }}">
        <div class="mb-3">
            <input type="number" name="id" value="{{$users->id}}" hidden>
            <label for="exampleInputEmail1" class="form-label">Name</label>
            <input type="text" name="name" value="{{$users->name}}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Email</label>
            <input type="email" name="email" value="{{$users->email}}" class="form-control">
        </div>
        <select name="usertype" id="usertype" hidden>           
            <option value="admin" @if($users && $users->usertype == 'admin') selected @endif> admin</option>
            <option value="user" @if($users && $users->usertype == 'user') selected @endif>user</option>
        </select>
        
        <button type="submit" class="btn btn-outline-primary">Save</button>
    </form>

</x-app-layout>
