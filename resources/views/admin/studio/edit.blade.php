@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">

            <h5 class="card-title mb-4">Edit Studio</h5>

            <form action="{{ route('admin.studio.update', $studio->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Nama Studio</label>
                    <input type="text" name="nama" class="form-control" value="{{ $studio->nama }}" required>
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.studio.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
@endsection
