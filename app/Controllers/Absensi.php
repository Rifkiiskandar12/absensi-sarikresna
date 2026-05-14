<?php

namespace App\Controllers;

use App\Models\AbsensiModel;

class Absensi extends BaseController
{
    public function proses_masuk()
    {
        $session = session();
        $absensiModel = new AbsensiModel();

        $id_karyawan = $session->get('id_user');
        $lokasi = $this->request->getPost('lokasi_masuk');
        $foto_base64 = $this->request->getPost('foto_masuk');

        $image_parts = explode(";base64,", $foto_base64);
        $image_base64 = base64_decode($image_parts[1]);
        
        $nama_file = 'masuk_' . $id_karyawan . '_' . time() . '.jpg';
        $path = FCPATH . 'uploads/absensi/' . $nama_file; 
        file_put_contents($path, $image_base64);

        $data_simpan = [
            'id_karyawan'  => $id_karyawan,
            'jam_masuk'    => date('H:i:s'),
            'foto_masuk'   => $nama_file,
            'lokasi_masuk' => $lokasi,
            'tanggal'      => date('Y-m-d'),
            'hari'         => date('w') 
        ];

        $absensiModel->insert($data_simpan);
        $session->setFlashdata('pesan', 'Berhasil Absen Masuk!');
        return redirect()->to('/dashboard');
    }

    // FUNGSI PROSES KELUAR DIPINDAH KE SINI
    public function proses_keluar()
    {
        $session = session();
        $absensiModel = new AbsensiModel();

        $id_karyawan = $session->get('id_user');
        $lokasi = $this->request->getPost('lokasi_keluar');
        $foto_base64 = $this->request->getPost('foto_keluar');
        $hari_ini = date('Y-m-d');

        $cek_absen = $absensiModel->where('id_karyawan', $id_karyawan)
                                  ->where('tanggal', $hari_ini)
                                  ->first();

        if ($cek_absen) {
            $image_parts = explode(";base64,", $foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            
            $nama_file = 'keluar_' . $id_karyawan . '_' . time() . '.jpg';
            $path = FCPATH . 'uploads/absensi/' . $nama_file;
            file_put_contents($path, $image_base64);

            $data_update = [
                'jam_keluar'    => date('H:i:s'),
                'foto_keluar'   => $nama_file,
                'lokasi_keluar' => $lokasi
            ];

            $absensiModel->update($cek_absen['id_absensi'], $data_update);
            $session->setFlashdata('pesan', 'Berhasil Absen Pulang! Selamat beristirahat.');
        }

        return redirect()->to('/dashboard');
    }
}