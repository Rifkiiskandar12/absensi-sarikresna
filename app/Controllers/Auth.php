<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\HrdModel;

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
        $hrdModel = new HrdModel();

        // Cek di tabel HRD/Admin/Pimpinan dulu
        $userHrd = $hrdModel->where('username', $username)->first();
        if ($userHrd && $password == $userHrd['password']) {
            $session->set([
                'id_user'  => $userHrd['id_hrd'],
                'username' => $userHrd['username'],
                'role'     => $userHrd['role'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/dashboard'); // Nanti kita buat dashboard-nya
        }

        // Jika tidak ada, cek di tabel Karyawan
        $userKaryawan = $karyawanModel->where('username', $username)->first();
        if ($userKaryawan && $password == $userKaryawan['password']) {
            $session->set([
                'id_user'  => $userKaryawan['id_karyawan'],
                'username' => $userKaryawan['username'],
                'role'     => 'Karyawan',
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
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}