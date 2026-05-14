<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Hrd extends BaseController
{
    public function simpan_karyawan()
    {
        $session = session();
        
        // Pastikan hanya HRD/Admin yang bisa mengakses fungsi ini
        if ($session->get('role') == 'Karyawan' || !$session->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $karyawanModel = new KaryawanModel();

        // Tangkap data dari form HRD
        $data_simpan = [
            'nik'          => $this->request->getPost('nik'),
            'nama'         => $this->request->getPost('nama'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'divisi'       => $this->request->getPost('divisi'),
            'status_aktif' => 1 // Langsung aktif saat dibuat
        ];

        // Simpan ke database
        $karyawanModel->insert($data_simpan);

        $session->setFlashdata('pesan', 'Akun Karyawan baru berhasil ditambahkan!');
        return redirect()->to('/dashboard');
    }
}