@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <h3>Edit Genre</h3>

        <div class="card mt-3">
            <div class="card-body">

                <form action="{{ route('admin.genre.update', $genre->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Genre</label>
                        <input type="text" name="nama" value="{{ $genre->nama }}"
                            class="form-control @error('nama') is-invalid @enderror">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.genre.index') }}" class="btn btn-secondary">Kembali</a>
                </form>

            </div>
        </div>
    </div>
@endsection
