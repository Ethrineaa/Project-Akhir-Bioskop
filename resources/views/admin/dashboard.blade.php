@extends('admin.layouts.app')

@section('content')
    <div class="row g-4">

        {{-- Total Penjualan --}}
        <div class="col-md-4">
            <div class="stat-card bg-soft-purple">
                <div class="stat-icon bg-purple">
                    <i class="ti ti-currency-dollar"></i>
                </div>
                <div class="stat-info">
                    <p>Total Penjualan Bulan Ini</p>
                    <h3>Rp {{ number_format($penjualanBulanIni ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Total Tiket Terjual --}}
        <div class="col-md-4">
            <div class="stat-card bg-soft-orange">
                <div class="stat-icon bg-orange">
                    <i class="ti ti-ticket"></i>
                </div>
                <div class="stat-info">
                    <p>Total Tiket Terjual</p>
                    <h3>{{ $tiketTerjual ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Film Tayang Hari Ini --}}
        <div class="col-md-4">
            <div class="stat-card bg-soft-green">
                <div class="stat-icon bg-green">
                    <i class="ti ti-movie"></i>
                </div>
                <div class="stat-info">
                    <p>Film Tayang Hari Ini</p>
                    <h3>{{ count($filmsToday ?? []) }}</h3>
                </div>
            </div>
        </div>

    </div>



    <div class="row mt-4">

        {{-- Grafik --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold mb-4">
                        <i class="ti ti-chart-line me-1"></i>
                        Grafik Penjualan Tiket
                    </h5>

                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Film Today --}}
        <div class="col-md-4">
            <h5 class="fw-semibold mb-3">
                <i class="ti ti-movie me-1"></i>
                Film yang Tayang Hari Ini
            </h5>

            @forelse ($filmsToday as $film)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ asset('posters/' . $film->poster) }}" width="60" height="80"
                            class="rounded me-3 object-fit-cover">

                        <div>
                            <h6 class="mb-1">{{ $film->judul }}</h6>
                            <small class="text-muted">
                                <i class="ti ti-category me-1"></i>
                                {{ $film->genre->nama }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <span>Tidak ada film tayang hari ini.</span>
                </div>
            @endforelse

        </div>

    </div>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'bar', // <-- GANTI JADI BAR
        data: {
            labels: {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Tiket Terjual',
                data: {!! json_encode($chartData ?? [12, 19, 7, 15, 22, 30, 18]) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.7)', // warna batang
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1,
                borderRadius: 6 // ujung batang melengkung
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

