<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function proses_login()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $karyawanModel = new KaryawanModel();

        // Sistem HANYA mencari di satu tabel terpusat (Karyawan)
        $user = $karyawanModel->where('username', $username)->first();

        if ($user && $password == $user['password']) {
            
            // Keamanan tambahan: Cek apakah akun di-suspend
            if ($user['status_aktif'] == 0) {
                $session->setFlashdata('msg', 'Akun Anda sedang ditangguhkan oleh Admin!');
                return redirect()->to('/');
            }

            // Set Session langsung mengambil dari ID dan Role yang ada di tabel
            $session->set([
                'id_user'    => $user['id_karyawan'],
                'username'   => $user['username'],
                'role'       => $user['role'], // Karyawan, HRD, Pimpinan, atau Admin
                'isLoggedIn' => true
            ]);
            
            return redirect()->to('/dashboard');
        }

        // Jika salah
        $session->setFlashdata('msg', 'Username atau Password salah!');
        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}