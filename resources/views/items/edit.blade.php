@extends('layouts.app')

@section('content')

<h1>Edit Item</h1>

<form action="{{ route('items.update', $item->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $item->name) }}" required>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $item->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Total</label>
        <input type="number" name="total" class="form-control"
               value="{{ old('total', $item->total) }}" required>
    </div>

    <div class="mb-3">
        <label>Repair</label>
        <input type="number" name="repair" class="form-control"
               value="{{ old('repair', $item->repair ?? 0) }}">
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection