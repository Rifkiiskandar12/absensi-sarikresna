<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\HrdModel;

class Admin extends BaseController
{
    // Fungsi untuk mengubah status Aktif / Suspend karyawan
    public function toggle_status($id_karyawan, $status_baru)
    {
        $karyawanModel = new KaryawanModel();
        
        // Update kolom status_aktif (1 = Aktif, 0 = Suspend)
        $karyawanModel->update($id_karyawan, ['status_aktif' => $status_baru]);
        
        $pesan = $status_baru == 1 ? 'Akun karyawan berhasil diaktifkan!' : 'Akun karyawan berhasil di-suspend!';
        return redirect()->to('/dashboard')->with('pesan', $pesan);
    }

    // Fungsi untuk mereset password karyawan
    public function reset_karyawan($id_karyawan)
    {
        $karyawanModel = new KaryawanModel();
        $karyawanModel->update($id_karyawan, ['password' => '123456']);
        
        return redirect()->to('/dashboard')->with('pesan', 'Password karyawan berhasil di-reset menjadi: 123456');
    }

    // Fungsi untuk mereset password jajaran Manajemen/HRD
    public function reset_manajemen($id_hrd)
    {
        $hrdModel = new HrdModel();
        $hrdModel->update($id_hrd, ['password' => '123456']);
        
        return redirect()->to('/dashboard')->with('pesan', 'Password manajemen berhasil di-reset menjadi: 123456');
    }

    // Fungsi untuk menambah akun karyawan baru via Admin
    public function simpan_karyawan()
    {
        $karyawanModel = new \App\Models\KaryawanModel();
        
        $data_simpan = [
            'nik'          => $this->request->getPost('nik'),
            'nama'         => $this->request->getPost('nama'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'divisi'       => $this->request->getPost('divisi'),
            'status_aktif' => 1 // Langsung aktif saat dibuat
        ];

        $karyawanModel->insert($data_simpan);
        return redirect()->to('/dashboard')->with('pesan', 'Akun Karyawan baru berhasil ditambahkan!');
    }
}