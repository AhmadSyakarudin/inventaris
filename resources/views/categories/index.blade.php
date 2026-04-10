@extends('layouts.app')

@section('content')

<h1>Table Category</h1>

<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">
    Tambah Category
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Division PJ</th>
            <th>Total Items</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->division_pj }}</td>
                <td>{{ $category->items->count() }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" 
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" 
                          method="POST" 
                          style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Category Ini??')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection