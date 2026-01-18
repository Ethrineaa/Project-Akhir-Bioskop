@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Edit Kursi</h4>

            <form action="{{ route('admin.kursi.update', $kursi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Studio</label>
                    <select name="studio_id" class="form-control" required>
                        @foreach ($studios as $s)
                            <option value="{{ $s->id }}" {{ $kursi->studio_id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Kursi</label>
                    <input type="text" name="nomor_kursi" class="form-control" value="{{ $kursi->nomor_kursi }}"
                        required>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('admin.kursi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
