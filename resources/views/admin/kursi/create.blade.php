@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Tambah Kursi</h4>

            <form action="{{ route('admin.kursi.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Studio</label>
                    <select name="studio_id" class="form-control" required>
                        <option value="">Pilih Studio</option>
                        @foreach ($studios as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Kursi</label>
                    <input type="text" name="nomor_kursi" class="form-control" placeholder="Contoh: A1" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.kursi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
