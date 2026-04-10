@extends('layouts.app')

@section('content')

<h1>Tambah Category</h1>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Division</label>
        <select name="division_pj" class="form-control" required>
            <option value="">-- pilih --</option>
            <option value="Tata Usaha">Tata Usaha</option>
            <option value="Sarpras">Sarpras</option>
            <option value="Tefa">Tefa</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection