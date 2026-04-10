@extends('layouts.app')

@section('content')

<h1>Edit Category</h1>

<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Division</label>
        <select name="division_pj" class="form-control" required>
            <option value="Tata Usaha" {{ $category->division_pj == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
            <option value="Sarpras" {{ $category->division_pj == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
            <option value="Tefa" {{ $category->division_pj == 'Tefa' ? 'selected' : '' }}>Tefa</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection