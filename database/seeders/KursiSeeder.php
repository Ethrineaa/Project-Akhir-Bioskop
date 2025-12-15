<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kursi;
use App\Models\Studio;

class KursiSeeder extends Seeder
{
    public function run(): void
    {
        $rows = ['A', 'B', 'C', 'D', 'E'];
        $jumlahPerBaris = 10;

        $studios = Studio::all();

        foreach ($studios as $studio) {
            foreach ($rows as $row) {
                for ($i = 1; $i <= $jumlahPerBaris; $i++) {

                    Kursi::firstOrCreate(
                        [
                            'studio_id'   => $studio->id,
                            'nomor_kursi' => $row . $i,
                        ]
                    );

                }
            }
        }
    }
}
