<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaksis')->insert([

            [
                'id_transaksi' => 'TRX001',
                'time' => '2026-11-01 08:00:00',
                'namaBarang' => 'Laptop',
                'hargaBarang' => 8500000,
                'kuantitas' => 2
            ],

            [
                'id_transaksi' => 'TRX002',
                'time' => '2026-11-01 09:00:00',
                'namaBarang' => 'Mouse',
                'hargaBarang' => 150000,
                'kuantitas' => 5
            ],

            [
                'id_transaksi' => 'TRX003',
                'time' => '2026-11-01 10:00:00',
                'namaBarang' => 'Keyboard',
                'hargaBarang' => 350000,
                'kuantitas' => 3
            ],

            [
                'id_transaksi' => 'TRX004',
                'time' => '2026-11-01 11:00:00',
                'namaBarang' => 'Monitor',
                'hargaBarang' => 2200000,
                'kuantitas' => 2
            ],

            [
                'id_transaksi' => 'TRX005',
                'time' => '2026-11-01 12:00:00',
                'namaBarang' => 'Printer',
                'hargaBarang' => 1800000,
                'kuantitas' => 1
            ],

            [
                'id_transaksi' => 'TRX006',
                'time' => '2026-11-02 08:00:00',
                'namaBarang' => 'Flashdisk',
                'hargaBarang' => 75000,
                'kuantitas' => 10
            ],

            [
                'id_transaksi' => 'TRX007',
                'time' => '2026-11-02 09:00:00',
                'namaBarang' => 'Harddisk',
                'hargaBarang' => 900000,
                'kuantitas' => 4
            ],

            [
                'id_transaksi' => 'TRX008',
                'time' => '2026-11-02 10:00:00',
                'namaBarang' => 'Headset',
                'hargaBarang' => 250000,
                'kuantitas' => 3
            ],

            [
                'id_transaksi' => 'TRX009',
                'time' => '2026-11-02 11:00:00',
                'namaBarang' => 'Webcam',
                'hargaBarang' => 450000,
                'kuantitas' => 2
            ],

            [
                'id_transaksi' => 'TRX010',
                'time' => '2026-11-02 12:00:00',
                'namaBarang' => 'Speaker',
                'hargaBarang' => 300000,
                'kuantitas' => 2
            ]

        ]);
    }
}
