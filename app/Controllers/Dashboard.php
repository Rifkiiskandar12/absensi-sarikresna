<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\CutiModel;
use App\Models\PengumumanModel; // <-- Integrasi Model Pengumuman Baru

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

        $karyawanModel = new \App\Models\KaryawanModel();
        $pengumumanModel = new PengumumanModel(); // <-- Inisialisasi Model Pengumuman

        // Ambil daftar divisi unik untuk dropdown dinamis di HRD dan Admin
        $default_divisi = ['IT / Fasilitas', 'HRD & Legal', 'Produksi', 'Pemasaran & Sales', 'Keuangan & Tax', 'Logistik & Gudang'];
        $db_divisi = $karyawanModel->select('divisi')->distinct()->findAll();
        $divisi_list = array_column($db_divisi, 'divisi');
        $data['daftar_divisi'] = array_unique(array_merge($default_divisi, $divisi_list));

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

            // AMBIL DATA PENGUMUMAN TERBARU UNTUK DISPLAY BANNER KARYAWAN
            $data['pengumuman_terbaru'] = $pengumumanModel->orderBy('tanggal_posting', 'DESC')->first();

            return view('dashboard/karyawan', $data);
            
        // ==========================================
        // DASHBOARD HRD
        // ==========================================
        } elseif ($role == 'HRD') {
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            
            $hari_ini  = date('Y-m-d');
            $bulan_ini = date('Y-m'); 
            
            $data['daftar_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                            ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                            ->where('tanggal', $hari_ini)->findAll();

            $data['daftar_cuti'] = $cutiModel->select('cuti.*, karyawan.nama')
                                        ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                                        ->where('status', 'Pending')->findAll();

            // SINKRONISASI: Menarik semua akun operasional yang bukan Admin agar divisi kustom tampil
            $semua_karyawan = $karyawanModel->where('divisi !=', 'Admin')->where('status_aktif', 1)->findAll();
            $peringatan_dini = [];
            $statistik_karyawan = [];

            foreach ($semua_karyawan as $k) {
                $hadir_bulan_ini = $absensiModel->where('id_karyawan', $k['id_karyawan'])
                                                ->like('tanggal', $bulan_ini, 'after')->countAllResults();
                $kpi = round(($hadir_bulan_ini / 22) * 100);
                
                $cuti_terpakai = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
                $sisa_cuti = 12 - ($cuti_terpakai['lama_cuti'] ?? 0);

                // Kelengkapan array data untuk disalurkan ke parameter JavaScript modal edit hrd
                $statistik_karyawan[] = [
                    'id_karyawan'     => $k['id_karyawan'],
                    'nik'             => $k['nik'],
                    'nama'            => $k['nama'],
                    'username'        => $k['username'],
                    'divisi'          => $k['divisi'],
                    'jam_masuk_shift' => $k['jam_masuk_shift'],
                    'hadir'           => $hadir_bulan_ini,
                    'kpi'             => $kpi > 100 ? 100 : $kpi,
                    'sisa_cuti'       => $sisa_cuti
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

            // AMBIL DATA PENGUMUMAN TERBARU UNTUK PREVIEW KOTAK FORM HRD
            $data['pengumuman_terbaru'] = $pengumumanModel->orderBy('tanggal_posting', 'DESC')->first();

            return view('dashboard/hrd', $data);

        // ==========================================
        // DASHBOARD PIMPINAN
        // ==========================================
        } elseif ($role == 'Pimpinan') {
            $absensiModel  = new AbsensiModel();
            $cutiModel     = new CutiModel();
            
            $tanggal_filter = $this->request->getGet('tanggal') ?? date('Y-m-d');

            // SINKRONISASI: Hitung dan tampilkan total akun operasional yang bukan Admin
            $data['total_karyawan'] = $karyawanModel->where('divisi !=', 'Admin')->where('status_aktif', 1)->countAllResults();
            $data['hadir_hari_ini'] = $absensiModel->where('tanggal', $tanggal_filter)->countAllResults();
            $data['cuti_hari_ini']  = $cutiModel->where('status', 'Diterima')
                                                ->where('tanggal_mulai_cuti <=', $tanggal_filter)
                                                ->where('tanggal_selesai_cuti >=', $tanggal_filter)->countAllResults();

            $data['rekap_absensi'] = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')
                                                  ->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')
                                                  ->where('tanggal', $tanggal_filter)->findAll();
            
            $data['daftar_karyawan'] = $karyawanModel->where('divisi !=', 'Admin')->where('status_aktif', 1)->findAll();
            $data['tanggal_filter'] = $tanggal_filter;
            // Tambahkan ini agar Pimpinan bisa melihat pengumuman terakhir
            $data['pengumuman_terbaru'] = $pengumumanModel->orderBy('tanggal_posting', 'DESC')->first();
            
            return view('dashboard/pimpinan', $data);

            return view('dashboard/pimpinan', $data);

        // ==========================================
        // DASHBOARD ADMIN
        // ==========================================
        } elseif ($role == 'Admin') {
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
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) return redirect()->to('/');

        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $periode = $tahun . '-' . $bulan; 

        $karyawanModel = new \App\Models\KaryawanModel();
        $absensiModel  = new AbsensiModel();
        $cutiModel     = new CutiModel();

        // SINKRONISASI: Tarik seluruh data karyawan non-Admin untuk kalkulasi report
        $semua_karyawan = $karyawanModel->where('divisi !=', 'Admin')->findAll();
        $data_laporan = [];

        foreach ($semua_karyawan as $k) {
            $hadir = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->countAllResults();
            $terlambat = $absensiModel->where('id_karyawan', $k['id_karyawan'])
                                      ->like('tanggal', $periode, 'after')
                                      ->where('jam_masuk >', $k['jam_masuk_shift']) 
                                      ->countAllResults();
            
            $cuti = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->where('status', 'Diterima')->like('tanggal_mulai_cuti', $periode, 'after')->first();
            $jml_cuti = $cuti['lama_cuti'] ?? 0;
            $cuti_total = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
            $sisa_cuti = 12 - ($cuti_total['lama_cuti'] ?? 0);

            $alfa = 22 - ($hadir + $jml_cuti);
            if ($alfa < 0) $alfa = 0; 
            $kpi = round(($hadir / 22) * 100);
            if ($kpi > 100) $kpi = 100;

            $data_laporan[] = [
                'nik'       => $k['nik'], 'nama'      => $k['nama'],
                'hadir'     => $hadir,    'terlambat' => $terlambat,
                'cuti'      => $jml_cuti, 'alfa'      => $alfa,
                'kpi'       => $kpi,      'sisa_cuti' => $sisa_cuti 
            ];
        }

        $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $data = ['periode_teks' => $nama_bulan[$bulan].' '.$tahun, 'data_laporan' => $data_laporan, 'nama_pencetak' => $session->get('username'), 'role_pencetak' => $session->get('role')];
        return view('dashboard/cetak_laporan', $data);
    }

    // ==========================================
    // PRIVILEGE: CETAK LAPORAN LOG HARIAN (PIMPINAN ONLY)
    // ==========================================
    public function cetak_laporan_harian()
    {
        $session = session();
        if ($session->get('role') != 'Pimpinan') return redirect()->to('/');

        $tanggal = $this->request->getPost('tanggal') ?? date('Y-m-d');
        $absensiModel = new AbsensiModel();
        $rekap_absensi = $absensiModel->select('absensi.*, karyawan.nama, karyawan.nik')->join('karyawan', 'karyawan.id_karyawan = absensi.id_karyawan')->where('tanggal', $tanggal)->findAll();

        $data = ['tanggal_teks' => date('d F Y', strtotime($tanggal)), 'rekap_absensi' => $rekap_absensi, 'nama_pencetak' => $session->get('username')];
        return view('dashboard/cetak_laporan_harian', $data);
    }

    // ==========================================
    // PRIVILEGE: EXPORT DATA BULANAN KE EXCEL
    // ==========================================
    public function export_excel()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) return redirect()->to('/');

        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $periode = $tahun . '-' . $bulan; 

        $karyawanModel = new \App\Models\KaryawanModel();
        $absensiModel  = new AbsensiModel();
        $cutiModel     = new CutiModel();

        // SINKRONISASI: Ambil data seluruh karyawan operasional non-Admin untuk rekap excel
        $semua_karyawan = $karyawanModel->where('divisi !=', 'Admin')->findAll();
        $data_laporan = [];

        foreach ($semua_karyawan as $k) {
            $hadir = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->countAllResults();
            $terlambat = $absensiModel->where('id_karyawan', $k['id_karyawan'])->like('tanggal', $periode, 'after')->where('jam_masuk >', $k['jam_masuk_shift'])->countAllResults();
            $cuti = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->where('status', 'Diterima')->like('tanggal_mulai_cuti', $periode, 'after')->first();
            
            $jml_cuti = $cuti['lama_cuti'] ?? 0;
            $cuti_total = $cutiModel->selectSum('lama_cuti')->where('id_karyawan', $k['id_karyawan'])->whereIn('status', ['Pending', 'Diterima'])->first();
            $sisa_cuti = 12 - ($cuti_total['lama_cuti'] ?? 0);

            $alfa = 22 - ($hadir + $jml_cuti);
            if ($alfa < 0) $alfa = 0; 
            $kpi = round(($hadir / 22) * 100);
            if ($kpi > 100) $kpi = 100;

            $data_laporan[] = [
                'nik' => $k['nik'], 'nama' => $k['nama'], 'hadir' => $hadir, 'terlambat' => $terlambat,
                'cuti' => $jml_cuti, 'alfa' => $alfa, 'kpi' => $kpi, 'sisa_cuti' => $sisa_cuti 
            ];
        }

        $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $data = ['periode_teks' => $nama_bulan[$bulan] . ' ' . $tahun, 'data_laporan' => $data_laporan];
        return view('dashboard/export_excel', $data);
    }

    // ==========================================
    // PRIVILEGE: UPDATE JADWAL SHIFT KARYAWAN
    // ==========================================
    public function update_shift()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) {
            return redirect()->to('/');
        }

        $id_karyawan = $this->request->getPost('id_karyawan');
        $shift_pilihan = $this->request->getPost('shift');

        $jam_masuk  = ($shift_pilihan == 'Siang') ? '13:00:00' : '08:00:00';
        $jam_pulang = ($shift_pilihan == 'Siang') ? '21:00:00' : '17:00:00';

        $karyawanModel = new \App\Models\KaryawanModel();
        $karyawanModel->update($id_karyawan, [
            'jam_masuk_shift'  => $jam_masuk,
            'jam_pulang_shift' => $jam_pulang
        ]);

        return redirect()->to('/dashboard')->with('pesan', 'Jadwal Shift karyawan berhasil diperbarui!');
    }

    // ==========================================
    // PRIVILEGE: PROSES UPDATE REVISI MASTER DATA KARYAWAN
    // ==========================================
    public function update_karyawan()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Admin', 'HRD'])) {
            return redirect()->to('/');
        }

        $id_karyawan = $this->request->getPost('id_karyawan');
        $karyawanModel = new \App\Models\KaryawanModel();

        $divisi = $this->request->getPost('divisi');
        if ($divisi === 'NEW_DIVISION') {
            $divisi = $this->request->getPost('divisi_baru');
        }

        $shift_pilihan = $this->request->getPost('shift');
        $jam_masuk  = ($shift_pilihan == 'Siang') ? '13:00:00' : '08:00:00';
        $jam_pulang = ($shift_pilihan == 'Siang') ? '21:00:00' : '17:00:00';

        $data_update = [
            'nik'              => $this->request->getPost('nik'),
            'nama'             => $this->request->getPost('nama'),
            'username'         => $this->request->getPost('username'),
            'divisi'           => $divisi,
            'jam_masuk_shift'  => $jam_masuk,
            'jam_pulang_shift' => $jam_pulang
        ];

        // Mempertahankan password teks biasa (plain text) sesuai arsitektur pengujian lokal
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data_update['password'] = $password; 
        }

        $karyawanModel->update($id_karyawan, $data_update);
        return redirect()->to('/dashboard')->with('pesan', 'Data master akun karyawan berhasil diperbarui!');
    }

    // ==========================================
    // POIN NO 5: SIMPAN & BROADCAST PENGUMUMAN RESMI
    // ==========================================
    public function simpan_pengumuman()
    {
        $session = session();
        if (!in_array($session->get('role'), ['Pimpinan', 'HRD'])) {
            return redirect()->to('/');
        }

        $pengumumanModel = new PengumumanModel();
        
        $pengumumanModel->insert([
            'judul'           => $this->request->getPost('judul'),
            'isi_pengumuman'  => $this->request->getPost('isi_pengumuman'),
            'tanggal_posting' => date('Y-m-d H:i:s'),
            'pembuat'         => $session->get('username')
        ]);

        return redirect()->to('/dashboard')->with('pesan', 'Pengumuman resmi berhasil di-broadcast ke seluruh karyawan!');
    }
}