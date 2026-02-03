@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Data {{ $title }}</h5>

            <a href="{{ route('admin.kursi.create') }}" class="btn btn-primary mb-4">+ Tambah Kursi</a>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Studio</th>
                            <th>Nomor Kursi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kursi as $index => $k)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $k->studio->nama }}</td>
                                <td>{{ $k->nomor_kursi }}</td>
                                <td>
                                    <a href="{{ route('admin.kursi.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form id="deleteForm{{ $k->id }}"
                                        action="{{ route('admin.kursi.destroy', $k->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')

                                        <button type="button" onclick="confirmDelete({{ $k->id }})"
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
        });

        function confirmDelete(id) {
            swal({
                    title: "Apakah Anda yakin?",
                    text: "Data tidak bisa dikembalikan!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) $('#deleteForm' + id).submit();
                });
        }
    </script>
@endsection
