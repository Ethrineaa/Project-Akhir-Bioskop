<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = ['pemesanan_id', 'bukti_pembayaran', 'status'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
