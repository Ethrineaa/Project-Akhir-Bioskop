@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Edit Jadwal</h3>

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Film</label>
                <select name="film_id" class="form-control" required>
                    @foreach ($films as $film)
                        <option value="{{ $film->id }}" {{ $film->id == $jadwal->film_id ? 'selected' : '' }}>
                            {{ $film->judul }} (Durasi: {{ $film->durasi }} menit)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Studio</label>
                <select name="studio_id" class="form-control" required>
                    @foreach ($studios as $studio)
                        <option value="{{ $studio->id }}" {{ $studio->id == $jadwal->studio_id ? 'selected' : '' }}>
                            {{ $studio->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                @php
                    $today = date('Y-m-d');
                    $minDate = $jadwal->tanggal < $today ? $jadwal->tanggal : $today;
                @endphp

                <input type="date" name="tanggal" class="form-control" value="{{ $jadwal->tanggal }}"
                    min="{{ $minDate }}" required>

            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam" class="form-control" value="{{ $jadwal->jam }}" required>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
