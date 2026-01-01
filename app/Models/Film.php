<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = ['judul', 'sinopsis', 'durasi', 'harga', 'genre_id', 'poster'];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
