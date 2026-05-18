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

            $data['absen_hari_ini'] = $absensiModel->where('id_karyawan', $id_karyawan)->where('tanggal', $hari_ini)->first();
            
            $total_cuti = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $id_karyawan)->whereIn('status', ['Pending', 'Diterima'])->first();
            $jatah_awal = 12;
            $data['sisa_cuti'] = $jatah_awal - ($total_cuti['lama_cuti'] ?? 0);

            $data['riwayat_absen'] = $absensiModel->where('id_karyawan', $id_karyawan)->orderBy('tanggal', 'DESC')->limit(5)->findAll();
            $data['riwayat_cuti'] = $cutiModel->where('id_karyawan', $id_karyawan)->orderBy('id_cuti', 'DESC')->findAll();

            return view('dashboard/karyawan', $data);
            
        // ==========================================
        // DASHBOARD HRD
        // ==========================================
        } elseif ($role == 'HRD') {
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            $karyawanModel = new \App\Models\KaryawanModel();
            
            $hari_ini  = date('Y-m-d');
            $bulan_ini = date('Y-m'); 
            
            $data['daftar_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                            ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                            ->where('tanggal', $hari_ini)->findAll();

            $data['daftar_cuti'] = $cutiModel->select('cuti.*, karyawan.nama')
                                        ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                                        ->where('status', 'Pending')->findAll();

            $semua_karyawan = $karyawanModel->where('divisi', 'Karyawan')->where('status_aktif', 1)->findAll();
            $peringatan_dini = [];
            $statistik_karyawan = [];

            foreach ($semua_karyawan as $k) {
                $hadir_bulan_ini = $absensiModel->where('id_karyawan', $k['id_karyawan'])
                                                ->like('tanggal', $bulan_ini, 'after')->countAllResults();
                $kpi = round(($hadir_bulan_ini / 22) * 100);
                
                $cuti_terpakai = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
                $sisa_cuti = 12 - ($cuti_terpakai['lama_cuti'] ?? 0);

                $statistik_karyawan[] = [
                    'nik'       => $k['nik'],
                    'nama'      => $k['nama'],
                    'hadir'     => $hadir_bulan_ini,
                    'kpi'       => $kpi > 100 ? 100 : $kpi,
                    'sisa_cuti' => $sisa_cuti
                ];

                $tiga_hari_lalu = date('Y-m-d', strtotime('-3 days'));
                $cek_absen = $absensiModel->where('id_karyawan', $k['id_karyawan'])->where('tanggal >=', $tiga_hari_lalu)->countAllResults();
                $cek_cuti = $cutiModel->where('id_karyawan', $k['id_karyawan'])->where('status', 'Diterima')->where('tanggal_selesai_cuti >=', $tiga_hari_lalu)->countAllResults();

                if ($cek_absen == 0 && $cek_cuti == 0) {
                    $peringatan_dini[] = $k['nama'];
                }
            }
            
            $data['statistik_karyawan'] = $statistik_karyawan;
            $data['peringatan_dini'] = $peringatan_dini;

            return view('dashboard/hrd', $data);

        // ==========================================
        // DASHBOARD PIMPINAN
        // ==========================================
        } elseif ($role == 'Pimpinan') {
            $karyawanModel = new \App\Models\KaryawanModel(); 
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            
            $tanggal_filter = $this->request->getGet('tanggal') ?? date('Y-m-d');

            $data['total_karyawan'] = $karyawanModel->where('divisi', 'Karyawan')->countAllResults();
            $data['hadir_hari_ini'] = $absensiModel->where('tanggal', $tanggal_filter)->countAllResults();
            $data['cuti_hari_ini']  = $cutiModel->where('status', 'Diterima')
                                                ->where('tanggal_mulai_cuti <=', $tanggal_filter)
                                                ->where('tanggal_selesai_cuti >=', $tanggal_filter)->countAllResults();

            $data['rekap_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                                  ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                                  ->where('tanggal', $tanggal_filter)->findAll();
            
            $data['tanggal_filter'] = $tanggal_filter;

            return view('dashboard/pimpinan', $data);

        // ==========================================
        // DASHBOARD ADMIN
        // ==========================================
        } elseif ($role == 'Admin') {
            $karyawanModel = new \App\Models\KaryawanModel();
            $hrdModel = new \App\Models\HrdModel(); 

            $data['daftar_karyawan'] = $karyawanModel->findAll();
            $data['daftar_manajemen'] = $hrdModel->findAll();

            return view('dashboard/admin', $data);

        } else {
            return redirect()->to('/');
        }
    }

    // ==========================================
    // PRIVILEGE: CETAK LAPORAN BULANAN (PDF)
    // ==========================================
    public function cetak_laporan()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) {
            return redirect()->to('/');
        }

        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $periode = $tahun . '-' . $bulan; 

        $karyawanModel = new \App\Models\KaryawanModel();
        $absensiModel  = new AbsensiModel();
        $cutiModel     = new CutiModel();

        $semua_karyawan = $karyawanModel->where('divisi', 'Karyawan')->findAll();
        $data_laporan = [];

        foreach ($semua_karyawan as $k) {
            $hadir = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->countAllResults();
            $terlambat = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->where('jam_masuk >', '08:00:00')->countAllResults();
            
            $cuti = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->where('status', 'Diterima')->like('tanggal_mulai_cuti', $periode, 'after')->first();
            $jml_cuti = $cuti['lama_cuti'] ?? 0;
            
            $cuti_total = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
            $sisa_cuti = 12 - ($cuti_total['lama_cuti'] ?? 0);

            $alfa = 22 - ($hadir + $jml_cuti);
            if ($alfa < 0) $alfa = 0; 

            $kpi = round(($hadir / 22) * 100);
            if ($kpi > 100) $kpi = 100;

            $data_laporan[] = [
                'nik'       => $k['nik'],
                'nama'      => $k['nama'],
                'hadir'     => $hadir,
                'terlambat' => $terlambat,
                'cuti'      => $jml_cuti,
                'alfa'      => $alfa,
                'kpi'       => $kpi,
                'sisa_cuti' => $sisa_cuti 
            ];
        }

        $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'October','11'=>'November','12'=>'Desember'];

        $data = [
            'periode_teks'  => $nama_bulan[$bulan] . ' ' . $tahun,
            'data_laporan'  => $data_laporan,
            'nama_pencetak' => $session->get('username'),
            'role_pencetak' => $session->get('role')
        ];

        return view('dashboard/cetak_laporan', $data);
    }

    // ==========================================
    // EXCLUSIVE PRIVILEGE: CETAK LAPORAN HARIAN
    // ==========================================
    public function cetak_laporan_harian()
    {
        $session = session();
        if ($session->get('role') != 'Pimpinan') {
            return redirect()->to('/');
        }

        $tanggal = $this->request->getPost('tanggal') ?? date('Y-m-d');
        $absensiModel = new AbsensiModel();

        $rekap_absensi = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                      ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                      ->where('tanggal', $tanggal)->findAll();

        $data = [
            'tanggal_teks'  => date('d F Y', strtotime($tanggal)),
            'rekap_absensi' => $rekap_absensi,
            'nama_pencetak' => $session->get('username')
        ];

        return view('dashboard/cetak_laporan_harian', $data);
    }

    // ==========================================
    // FITUR AMUNISI BARU: EXPORT DATA KE EXCEL
    // ==========================================
    public function export_excel()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) {
            return redirect()->to('/');
        }

        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $periode = $tahun . '-' . $bulan; 

        $karyawanModel = new \App\Models\KaryawanModel();
        $absensiModel  = new AbsensiModel();
        $cutiModel     = new CutiModel();

        $semua_karyawan = $karyawanModel->where('divisi', 'Karyawan')->findAll();
        $data_laporan = [];

        foreach ($semua_karyawan as $k) {
            $hadir = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->countAllResults();
            $terlambat = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->where('jam_masuk >', '08:00:00')->countAllResults();
            $cuti = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->where('status', 'Diterima')->like('tanggal_mulai_cuti', $periode, 'after')->first();
            $jml_cuti = $cuti['lama_cuti'] ?? 0;
            
            $cuti_total = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
            $sisa_cuti = 12 - ($cuti_total['lama_cuti'] ?? 0);

            $alfa = 22 - ($hadir + $jml_cuti);
            if ($alfa < 0) $alfa = 0; 

            $kpi = round(($hadir / 22) * 100);
            if ($kpi > 100) $kpi = 100;

            $data_laporan[] = [
                'nik'       => $k['nik'],
                'nama'      => $k['nama'],
                'hadir'     => $hadir,
                'terlambat' => $terlambat,
                'cuti'      => $jml_cuti,
                'alfa'      => $alfa,
                'kpi'       => $kpi,
                'sisa_cuti' => $sisa_cuti 
            ];
        }

        $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

        $data = [
            'periode_teks' => $nama_bulan[$bulan] . ' ' . $tahun,
            'data_laporan' => $data_laporan
        ];

        return view('dashboard/export_excel', $data);
    }
}