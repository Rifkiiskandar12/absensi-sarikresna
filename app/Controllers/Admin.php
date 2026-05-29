<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
// HrdModel sudah dihapus karena kita sudah memakai sistem 1 tabel terpusat

class Admin extends BaseController
{
    // Fungsi untuk mengubah status Aktif / Suspend (Berlaku untuk semua Role)
    public function toggle_status($id_karyawan, $status_baru)
    {
        $karyawanModel = new KaryawanModel();
        
        // Update kolom status_aktif (1 = Aktif, 0 = Suspend)
        $karyawanModel->update($id_karyawan, ['status_aktif' => $status_baru]);
        
        $pesan = $status_baru == 1 ? 'Akun berhasil diaktifkan!' : 'Akun berhasil di-suspend!';
        return redirect()->to('/dashboard')->with('pesan', $pesan);
    }

    // Fungsi untuk mereset password (Sekarang berlaku untuk Karyawan, HRD, Pimpinan, dan Admin)
    public function reset_karyawan($id_karyawan)
    {
        $karyawanModel = new KaryawanModel();
        $karyawanModel->update($id_karyawan, ['password' => '123456']);
        
        return redirect()->to('/dashboard')->with('pesan', 'Password akun berhasil di-reset menjadi: 123456');
    }

    // (Fungsi reset_manajemen telah dihapus total karena sudah ter-cover oleh fungsi reset_karyawan di atas)

    // Fungsi untuk menambah akun baru via Admin
    public function simpan_karyawan()
    {
        $karyawanModel = new \App\Models\KaryawanModel();
        
        // Cek apakah memilih membuat divisi baru kustom
        $divisi = $this->request->getPost('divisi');
        if ($divisi === 'NEW_DIVISION') {
            $divisi = $this->request->getPost('divisi_baru'); // Tangkap input ketik manual
        }

        $shift_pilihan = $this->request->getPost('shift');
        $jam_masuk  = ($shift_pilihan == 'Siang') ? '13:00:00' : '08:00:00';
        $jam_pulang = ($shift_pilihan == 'Siang') ? '21:00:00' : '17:00:00';
        
        // Tangkap Role / Hak Akses (Jika kosong, default jadikan Karyawan)
        $role = $this->request->getPost('role') ?? 'Karyawan';
        
        $data_simpan = [
            'nik'              => $this->request->getPost('nik'),
            'nama'             => $this->request->getPost('nama'),
            'username'         => $this->request->getPost('username'),
            'password'         => $this->request->getPost('password'),
            'role'             => $role,       // <--- SEKARANG ROLE IKUT DISIMPAN KE DATABASE
            'divisi'           => $divisi, 
            'jam_masuk_shift'  => $jam_masuk,
            'jam_pulang_shift' => $jam_pulang,
            'status_aktif'     => 1 
        ];

        $karyawanModel->insert($data_simpan);
        
        return redirect()->to('/dashboard')->with('pesan', 'Akun ' . $role . ' baru berhasil ditambahkan ke dalam sistem!');
    }
}