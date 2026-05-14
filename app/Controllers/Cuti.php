<?php

namespace App\Controllers;

use App\Models\CutiModel;

class Cuti extends BaseController
{
    public function ajukan()
    {
        $cutiModel = new CutiModel();
        
        // Menghitung selisih hari
        $tgl_mulai = new \DateTime($this->request->getPost('tgl_mulai'));
        $tgl_selesai = new \DateTime($this->request->getPost('tgl_selesai'));
        $diff = $tgl_mulai->diff($tgl_selesai)->days + 1;

        $cutiModel->insert([
            'id_karyawan'          => session()->get('id_user'),
            'tanggal_pengajuan'    => date('Y-m-d'),
            'tanggal_mulai_cuti'   => $this->request->getPost('tgl_mulai'),
            'tanggal_selesai_cuti' => $this->request->getPost('tgl_selesai'),
            'alasan_cuti'          => $this->request->getPost('alasan'),
            'lama_cuti'            => $diff,
            'status'               => 'Pending'
        ]);

        // ... bagian akhir fungsi ajukan()
        return redirect()->to('/dashboard')->with('pesan', 'Permintaan cuti Anda sudah masuk ke atasan. Anda akan dinotify di dashboard ini jika sudah di-acc atau tidak oleh manajemen PT Sari Kresna Kimia.');
    }

    // ... tambahkan method ini di dalam class Cuti

    public function setuju($id)
    {
        $cutiModel = new CutiModel();
        $cutiModel->update($id, ['status' => 'Diterima']);
        return redirect()->to('/dashboard')->with('pesan', 'Permintaan cuti berhasil disetujui.');
    }

    public function tolak($id)
    {
        $cutiModel = new CutiModel();
        $cutiModel->update($id, ['status' => 'Ditolak']);
        return redirect()->to('/dashboard')->with('pesan', 'Permintaan cuti telah ditolak.');
    }
}