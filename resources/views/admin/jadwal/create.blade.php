@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Tambah Jadwal Baru</h3>

        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Film</label>
                <select name="film_id" class="form-control" required>
                    <option value="">-- Pilih Film --</option>
                    @foreach ($films as $film)
                        <option value="{{ $film->id }}">{{ $film->judul }} (Durasi: {{ $film->durasi }} menit)</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Studio</label>
                <select name="studio_id" class="form-control" required>
                    <option value="">-- Pilih Studio --</option>
                    @foreach ($studios as $studio)
                        <option value="{{ $studio->id }}">{{ $studio->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" min="{{ date('Y-m-d') }}" required>

            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam" class="form-control" required>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
