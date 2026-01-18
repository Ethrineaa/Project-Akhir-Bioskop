@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Data {{ $title }}</h5>

        <a href="{{ route('admin.studio.create') }}" class="btn btn-primary mb-4">
            + Tambah Studio
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="datatable" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($studios as $index => $studio)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $studio->nama }}</td>
                        <td>{{ $studio->kapasitas }}</td>
                        <td>
                            <!-- tombol modal -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addKursiModal{{ $studio->id }}">
                                <i class="bi bi-plus-circle"></i> Kursi
                            </button>

                            <a href="{{ route('admin.studio.edit', $studio->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form id="deleteForm{{ $studio->id }}"
                                action="{{ route('admin.studio.destroy', $studio->id) }}"
                                method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $studio->id }})"
                                    class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        {{-- ===== MODALS DIPINDAH KE SINI ===== --}}
        @foreach ($studios as $studio)
        <div class="modal fade" id="addKursiModal{{ $studio->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('admin.kursi.store') }}">
                    @csrf
                    <input type="hidden" name="studio_id" value="{{ $studio->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kursi - {{ $studio->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Nomor Kursi</label>
                        <input type="text" name="nomor_kursi" class="form-control"
                            placeholder="contoh: A1" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
        @endforeach
        {{-- ===================================== --}}

    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();

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
