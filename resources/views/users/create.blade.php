@extends('layouts.app')

@section('content')
<div class="card p-4">

    <form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">

        @csrf
        @if($user->exists)
            @method('PUT') 
        @endif

        <div class="mb-2">
            <label>Name</label>
            <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-2">
            <label>Email</label>
            <input name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-2">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        <div class="mb-2">
            <label>Password</label>
            <input name="password" type="password" class="form-control">
            <small>Leave blank if not changing</small>
        </div>

        <button class="btn btn-primary">Save</button>

    </form>

</div>
@endsection