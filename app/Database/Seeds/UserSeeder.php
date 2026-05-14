<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Data untuk tabel HRD (Pimpinan & HRD)
        $dataHrd = [
            [
                'hrd'          => 'Pak Gunawan',
                'username'     => 'admin',
                'password'     => 'admin123', // Pakai plain text dulu sesuai controller kamu
                'role'         => 'Admin',
                'status_aktif' => 1
            ],
            [
                'hrd'          => 'Bu Rina (HRD)',
                'username'     => 'hrd',
                'password'     => 'hrd123',
                'role'         => 'HRD',
                'status_aktif' => 1
            ],
            [
                'hrd'          => 'Bapak Direktur',
                'username'     => 'pimpinan',
                'password'     => 'pimpinan123',
                'role'         => 'Pimpinan',
                'status_aktif' => 1
            ]
        ];

        // 2. Data untuk tabel Karyawan
        $dataKaryawan = [
            [
                'nik'          => 'KRY-001',
                'nama'         => 'Muhammad Daffa',
                'username'     => 'daffa',
                'password'     => 'daffa123',
                'divisi'       => 'Karyawan',
                'status_aktif' => 1
            ],
            [
                'nik'          => 'KRY-002',
                'nama'         => 'Siti Aminah',
                'username'     => 'siti',
                'password'     => 'siti123',
                'divisi'       => 'Karyawan',
                'status_aktif' => 1
            ]
        ];

        // Eksekusi: Suntikkan data ke database
        // Pakai insertBatch() supaya bisa langsung masukin banyak data sekaligus
        $this->db->table('hrd')->insertBatch($dataHrd);
        $this->db->table('karyawan')->insertBatch($dataKaryawan);
        
        echo "Data User berhasil disuntikkan ke database! \n";
    }
}