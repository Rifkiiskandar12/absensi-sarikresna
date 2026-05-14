<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\CutiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $role = $session->get('role');
        $id_karyawan = $session->get('id_user');
        
        $data = [
            'username' => $session->get('username'),
            'role'     => $role
        ];

        if ($role == 'Karyawan') {
        $absensiModel = new AbsensiModel();
        $cutiModel = new CutiModel();
        $hari_ini = date('Y-m-d');

        // 1. Ambil status absen hari ini
        $data['absen_hari_ini'] = $absensiModel->where('id_karyawan', $id_karyawan)
                                            ->where('tanggal', $hari_ini)
                                            ->first();
        
        // 2. Hitung total jatah cuti (Misal jatah awal 12 hari)
        $total_cuti = $cutiModel->selectSum('lama_cuti')
                            ->where('id_karyawan', $id_karyawan)
                            ->whereIn('status', ['Pending', 'Diterima']) // <-- Tambahkan filter ini
                            ->first();
        
        $jatah_awal = 12;
        $digunakan = $total_cuti['lama_cuti'] ?? 0;
        $data['sisa_cuti'] = $jatah_awal - $digunakan;

        // 3. Riwayat absen & cuti
        $data['riwayat_absen'] = $absensiModel->where('id_karyawan', $id_karyawan)
                                            ->orderBy('tanggal', 'DESC')
                                            ->limit(5)
                                            ->findAll();

        $data['riwayat_cuti'] = $cutiModel->where('id_karyawan', $id_karyawan)
                                        ->orderBy('id_cuti', 'DESC')
                                        ->findAll();

        return view('dashboard/karyawan', $data);
            
        } elseif ($role == 'HRD') {
        $absensiModel = new AbsensiModel();
        $cutiModel = new CutiModel();
        $hari_ini = date('Y-m-d');
        
        // Data Absensi Hari Ini
        $data['daftar_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                            ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                            ->where('tanggal', $hari_ini)
                                            ->findAll();

        // DATA BARU: Mengambil Permintaan Cuti yang statusnya 'Pending'
        $data['daftar_cuti'] = $cutiModel->select('cuti.*, karyawan.nama')
                                        ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                                        ->where('status', 'Pending')
                                        ->findAll();

        return view('dashboard/hrd', $data);
        } elseif ($role == 'Pimpinan') {
            $karyawanModel = new \App\Models\KaryawanModel(); 
            $absensiModel = new AbsensiModel();
            $cutiModel = new CutiModel();
            $hari_ini = date('Y-m-d');

            $data['total_karyawan'] = $karyawanModel->where('divisi', 'Karyawan')->countAllResults();
            $data['hadir_hari_ini'] = $absensiModel->where('tanggal', $hari_ini)->countAllResults();
            $data['cuti_hari_ini']  = $cutiModel->where('status', 'Diterima')
                                                ->where('tanggal_mulai_cuti <=', $hari_ini)
                                                ->where('tanggal_selesai_cuti >=', $hari_ini)
                                                ->countAllResults();

            $data['rekap_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                                  ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                                  ->where('tanggal', $hari_ini)
                                                  ->findAll();

            return view('dashboard/pimpinan', $data);

        } elseif ($role == 'Admin') {
            // LOGIKA KHUSUS ADMIN
            $karyawanModel = new \App\Models\KaryawanModel();
            $hrdModel = new \App\Models\HrdModel(); // Model untuk tabel hrd (Admin, HRD, Pimpinan)

            // Ambil semua data akun di sistem
            $data['daftar_karyawan'] = $karyawanModel->findAll();
            $data['daftar_manajemen'] = $hrdModel->findAll();

            return view('dashboard/admin', $data);

        } else {
            return redirect()->to('/');
        }
    }
}