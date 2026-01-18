@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Data Jadwal</h5>

            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary mb-4">
                + Tambah Jadwal
            </a>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table id="datatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Film</th>
                        <th>Studio</th>
                        <th>Tanggal</th>
                        <th>Jam Tayang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal->film->judul }}</td>
                            <td>{{ $jadwal->studio->nama }}</td>
                            <td>{{ $jadwal->tanggal }}</td>
                            <td>{{ $jadwal->jam }}</td>
                            <td>

                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form id="deleteForm{{ $jadwal->id }}"
                                    action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $jadwal->id }})"
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();

            // ✅ SweetAlert sukses
            @if (session('success'))
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    button: "OK"
                });
            @endif

            // ✅ SweetAlert error (genre gagal dihapus karena relasi)
            @if (session('error'))
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
