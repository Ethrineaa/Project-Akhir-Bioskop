<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Struk</title>

<style>
@page {
    size: 58mm auto;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: monospace;
}

.struk {
    width: 58mm;
    padding: 4mm 3mm;
    box-sizing: border-box;
    font-size: 12px;
}

.center { text-align: center; }
.bold { font-weight: bold; }

.line {
    border-top: 1px dashed #000;
    margin: 6px 0;
}

.row {
    display: flex;
    justify-content: space-between;
}
.small { font-size: 11px; }
</style>
</head>

<body onload="window.print(); window.onafterprint = () => window.close();">

<div class="struk">

    <div class="center bold">BIOSKOP</div>
    <div class="center small">Tiket Nonton</div>

    <div class="line"></div>

    <div class="row">
        <span>ID</span>
        <span>TX-{{ str_pad($pemesanan->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="line"></div>

    <p class="bold">{{ $pemesanan->jadwal->film->judul }}</p>

    <div class="row"><span>Tanggal</span><span>{{ $pemesanan->jadwal->tanggal }}</span></div>
    <div class="row"><span>Jam</span><span>{{ $pemesanan->jadwal->jam }}</span></div>
    <div class="row"><span>Studio</span><span>{{ $pemesanan->jadwal->studio->nama }}</span></div>
    <div class="row"><span>Kursi</span><span>{{ $pemesanan->kursi->pluck('nomor_kursi')->implode(', ') }}</span></div>

    <div class="line"></div>

    <div class="row bold">
        <span>TOTAL</span>
        <span>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="line"></div>

    <p class="center small">
        Terima kasih 🙏<br>
        Selamat menonton
    </p>

</div>

</body>
</html>
