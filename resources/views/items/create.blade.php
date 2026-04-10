@extends('layouts.app')

@section('content')

<h1>Tambah Item</h1>

<form action="{{ route('items.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            <option value="">-- pilih category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Total</label>
        <input type="number" name="total" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Repair</label>
        <input type="number" name="repair" class="form-control" value="0">
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection