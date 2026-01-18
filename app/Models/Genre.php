<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['nama'];

    public function film()
    {
        return $this->hasMany(Film::class);
    }
}
