@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">

            <h5 class="card-title fw-semibold mb-4">
                Data {{ $title ?? 'Film' }}
            </h5>

            <a href="{{ route('admin.film.create') }}" class="btn btn-primary mb-4">
                + Tambah Data {{ $title ?? 'Film' }}
            </a>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Poster</th>
                            <th>Judul</th>
                            <th>Genre</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($films as $index => $film)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    @if ($film->poster)
                                        <img src="{{ asset('posters/' . $film->poster) }}" width="60">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $film->judul }}</td>
                                <td>{{ $film->genre->nama }}</td>
                                <td>{{ $film->durasi }} menit</td>
                                <td>Rp {{ number_format($film->harga, 0, ',', '.') }}</td>

                                <td>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.film.edit', $film->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form id="deleteForm{{ $film->id }}"
                                        action="{{ route('admin.film.destroy', $film->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" onclick="confirmDelete({{ $film->id }})"
                                            class="btn btn-danger btn-sm">
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
