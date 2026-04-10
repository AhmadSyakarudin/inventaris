@extends('layouts.app')

@section('content')

<h3>Admin - User Management</h3>

<a href="{{ route('users.create') }}" class="btn btn-primary mb-2">Create User</a>
<a href="{{ route('users.export') }}" class="btn btn-success mb-2">Export CSV</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>

                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection