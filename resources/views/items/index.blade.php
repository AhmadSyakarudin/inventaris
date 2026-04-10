@extends('layouts.app')

@section('content')

<h1>Data Items</h1>

<a href="{{ route('items.create') }}" class="btn btn-primary mb-3">
    Tambah Item
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Total</th>
            <th>Repair</th>
            <th>Lending</th>
            <th>Borrowed</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name ?? '-' }}</td>
            <td>{{ $item->total }}</td>
            <td>{{ $item->repair ?? 0 }}</td>
            <td>{{ $item->lending ?? 0 }}</td>
            <td>{{ $item->borrowed ?? 0 }}</td>

            <td>
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin mau hapus?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection