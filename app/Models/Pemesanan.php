<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanans';
    protected $fillable = ['jadwal_id', 'user_id', 'jumlah_tiket', 'total_harga'];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function kursis()
    {
        return $this->belongsToMany(Kursi::class, 'kursi_pemesanan', 'pemesanan_id', 'kursi_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}
jj
