@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Tambah Film</h3>

        <form action="{{ route('admin.film.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control">
            </div>

            <div class="mb-3">
                <label>Sinopsis</label>
                <textarea name="sinopsis" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label>Durasi (menit)</label>
                <input type="number" name="durasi" class="form-control">
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control">
            </div>

            <div class="mb-3">
                <label>Genre</label>
                <select name="genre_id" class="form-control">
                    @foreach ($genres as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Poster</label>
                <input type="file" name="poster" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.film.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
