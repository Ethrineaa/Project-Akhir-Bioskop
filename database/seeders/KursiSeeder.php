<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kursi;
use App\Models\Studio;

class KursiSeeder extends Seeder
{
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {

            if ($studio->kapasitas == 50) {
                $rows = ['A','B','C','D','E'];
                $jumlahPerBaris = 10;
            } elseif ($studio->kapasitas == 75) {
                $rows = ['A','B','C','D','E'];
                $jumlahPerBaris = 15;
            } else { // 100
                $rows = ['A','B','C','D','E','F','G','H','I','J'];
                $jumlahPerBaris = 10;
            }

            foreach ($rows as $row) {
                for ($i = 1; $i <= $jumlahPerBaris; $i++) {
                    Kursi::firstOrCreate([
                        'studio_id'   => $studio->id,
                        'nomor_kursi' => $row . $i,
                    ]);
                }
            }
        }
    }
}
