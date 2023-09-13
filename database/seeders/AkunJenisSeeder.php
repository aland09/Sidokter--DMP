<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkunJenis;

class AkunJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $akun_jenis = [
            [
                'kode_akun' => '5.1.01',
                'nama_akun' => 'Belanja Pegawai',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.1.02',
                'nama_akun' => 'Belanja Barang dan Jasa',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.1.03',
                'nama_akun' => 'Belanja Bunga',
                'keterangan' => 'Permanen',
            ],
              [
                'kode_akun' => '5.1.04',
                'nama_akun' => 'Belanja Subsidi',
                'keterangan' => 'Permanen',
            ],
              [
                'kode_akun' => '5.1.05',
                'nama_akun' => 'Belanja Hibah',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.1.06',
                'nama_akun' => 'Belanja Bantuan Sosial',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.2.01',
                'nama_akun' => 'Belanja Modal Tanah',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.2.02',
                'nama_akun' => 'Belanja Modal Peralatan dan Mesin',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.2.03',
                'nama_akun' => 'Belanja Modal Gedung dan Bangunan',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.2.04',
                'nama_akun' => 'Belanja Modal Jalan, Jaringan, dan Irigasi',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.2.05',
                'nama_akun' => 'Belanja Modal Aset Tetap Lainnya',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '5.4.02',
                'nama_akun' => 'Belanja Bantuan Keuangan',
                'keterangan' => 'Permanen',
            ],
            [
                'kode_akun' => '6.2.03',
                'nama_akun' => 'Pembayaran Cicilan Pokok Utang yang Jatuh Tempo',
                'keterangan' => 'Permanen',
            ]
        ];

        foreach ($akun_jenis as $akun) {
            AkunJenis::create($akun);
        }
    }
}
