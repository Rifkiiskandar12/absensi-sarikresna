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

        // ==========================================
        // DASHBOARD KARYAWAN
        // ==========================================
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
                                ->whereIn('status', ['Pending', 'Diterima']) 
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
            
        // ==========================================
        // DASHBOARD HRD
        // ==========================================
        } elseif ($role == 'HRD') {
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            $karyawanModel = new \App\Models\KaryawanModel();
            
            $hari_ini  = date('Y-m-d');
            $bulan_ini = date('Y-m'); // Format: 2026-05
            
            // 1. Data Absensi Hari Ini
            $data['daftar_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                            ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                            ->where('tanggal', $hari_ini)
                                            ->findAll();

            // 2. Mengambil Permintaan Cuti yang statusnya 'Pending'
            $data['daftar_cuti'] = $cutiModel->select('cuti.*, karyawan.nama')
                                        ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                                        ->where('status', 'Pending')
                                        ->findAll();

            // ---------------------------------------------------------
            // FITUR NO 2: KPI KINERJA & PERINGATAN DINI (ALFA 3 HARI)
            // ---------------------------------------------------------
            $semua_karyawan = $karyawanModel->where('divisi', 'Karyawan')->where('status_aktif', 1)->findAll();
            $peringatan_dini = [];
            $kpi_karyawan = [];
            
            $tiga_hari_lalu = date('Y-m-d', strtotime('-3 days'));

            foreach ($semua_karyawan as $k) {
                // A. Hitung KPI (Persentase Kehadiran Bulan Ini) - Asumsi 22 hari kerja sebulan
                $hadir_bulan_ini = $absensiModel->where('id_karyawan', $k['id_karyawan'])
                                                ->like('tanggal', $bulan_ini, 'after')
                                                ->countAllResults();
                $kpi = round(($hadir_bulan_ini / 22) * 100);
                
                $kpi_karyawan[] = [
                    'nama'  => $k['nama'],
                    'hadir' => $hadir_bulan_ini,
                    'kpi'   => $kpi > 100 ? 100 : $kpi // Cegah lebih dari 100% jika masuk lembur/weekend
                ];

                // B. Deteksi Peringatan Dini (Tidak absen & tidak sedang cuti dlm 3 hari terakhir)
                $cek_absen = $absensiModel->where('id_karyawan', $k['id_karyawan'])
                                          ->where('tanggal >=', $tiga_hari_lalu)
                                          ->countAllResults();
                
                $cek_cuti = $cutiModel->where('id_karyawan', $k['id_karyawan'])
                                      ->where('status', 'Diterima')
                                      ->where('tanggal_selesai_cuti >=', $tiga_hari_lalu)
                                      ->countAllResults();

                if ($cek_absen == 0 && $cek_cuti == 0) {
                    $peringatan_dini[] = $k['nama'];
                }
            }
            
            $data['kpi_karyawan']    = $kpi_karyawan;
            $data['peringatan_dini'] = $peringatan_dini;

            return view('dashboard/hrd', $data);

        // ==========================================
        // DASHBOARD PIMPINAN
        // ==========================================
        } elseif ($role == 'Pimpinan') {
            $karyawanModel = new \App\Models\KaryawanModel(); 
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            $hari_ini      = date('Y-m-d');

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

        // ==========================================
        // DASHBOARD ADMIN
        // ==========================================
        } elseif ($role == 'Admin') {
            $karyawanModel = new \App\Models\KaryawanModel();
            $hrdModel = new \App\Models\HrdModel(); 

            // Ambil semua data akun di sistem
            $data['daftar_karyawan'] = $karyawanModel->findAll();
            $data['daftar_manajemen'] = $hrdModel->findAll();

            return view('dashboard/admin', $data);

        } else {
            return redirect()->to('/');
        }
    }
}