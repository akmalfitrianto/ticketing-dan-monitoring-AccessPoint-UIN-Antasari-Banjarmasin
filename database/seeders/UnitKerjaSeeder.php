<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UnitKerjaSeeder extends Seeder
{
    
    public function run(): void
    {
        $unitKerjaList = [
            'Bagian Administrasi dan Umum (Pusat)',
            'Bagian Akademik dan Kemahasiswaan (Mikwa Pusat)',
            'FAKULTAS DAKWAH DAN ILMU KOMUNIKASI',
            'Fakultas Ekonomi dan Bisnis Islam',
            'Fakultas Syariah',
            'Fakultas Tarbiyah dan Keguruan',
            'Fakultas Ushuluddin dan Humaniora',
            'Layanan Masyarakat',
            'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LP2M)',
            'Lembaga Penjaminan Mutu (LPM)',
            'Pascasarjana',
            'Perpustakaan',
            'Satuan Pengawasan Internal (SPI)',
            'Unit Pengembangan Bahasa (UPB)',
            'Unit Pengembangan Bisnis',
            'Unit Teknologi Informasi dan Pangkalan Data (UTIPD)',
            'UPT. Ma\'had al - Jami\'ah',
            'UPT. Pengembangan Kewirausahaan dan Karier (UPKK)',
        ];

        $data = [];
        foreach ($unitKerjaList as $unit) {
            $data[] = [
                'nama' => $unit,
                'aktif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('unit_kerja')->insert($data);
    }
}
