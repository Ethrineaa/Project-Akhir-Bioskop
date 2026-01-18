@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">

            <h5 class="card-title fw-semibold mb-4">
                Data {{ $title ?? 'Genre' }}
            </h5>

            <a href="{{ route('admin.genre.create') }}" class="btn btn-primary mb-4">
                + Tambah Data {{ $title ?? 'Genre' }}
            </a>

            <div class="table-responsive">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Genre</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($genres as $index => $genre)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $genre->nama }}</td>
                                <td>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.genre.edit', $genre->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form id="deleteForm{{ $genre->id }}"
                                        action="{{ route('admin.genre.destroy', $genre->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" onclick="confirmDelete({{ $genre->id }})"
                                            class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
@endsection


@section('js')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();

        // ✅ SweetAlert sukses
        @if(session('success'))
            swal({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK"
            });
        @endif

        // ✅ SweetAlert error (genre gagal dihapus karena relasi)
        @if(session('error'))
            swal({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                button: "OK"
            });
        @endif
    });

    function confirmDelete(id) {
        swal({
            title: "Apakah anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $('#deleteForm' + id).submit();
            } else {
                swal("Data tidak jadi dihapus!", {
                    icon: "info"
                });
            }
        });
    }
</script>
@endsection

@endsection
