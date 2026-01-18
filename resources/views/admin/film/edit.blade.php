@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Edit Film</h3>

        <form action="{{ route('admin.film.update', $film->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="{{ $film->judul }}">
            </div>

            <div class="mb-3">
                <label>Sinopsis</label>
                <textarea name="sinopsis" class="form-control" rows="4">{{ $film->sinopsis }}</textarea>
            </div>

            <div class="mb-3">
                <label>Durasi</label>
                <input type="number" name="durasi" class="form-control" value="{{ $film->durasi }}">
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" value="{{ $film->harga }}">
            </div>

            <div class="mb-3">
                <label>Genre</label>
                <select name="genre_id" class="form-control">
                    @foreach ($genres as $g)
                        <option value="{{ $g->id }}" {{ $film->genre_id == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Poster</label><br>
                @if ($film->poster)
                    <img src="{{ asset('posters/' . $film->poster) }}" width="120" class="mb-3">
                @endif
                <input type="file" name="poster" class="form-control">
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.film.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
