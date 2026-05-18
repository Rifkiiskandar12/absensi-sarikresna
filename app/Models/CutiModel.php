<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiModel extends Model
{
    protected $table            = 'cuti';
    protected $primaryKey       = 'id_cuti';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $allowedFields    = [
        'id_karyawan', 'id_hrd', 'tanggal_pengajuan', 'tanggal_diterima',
        'tanggal_mulai_cuti', 'tanggal_selesai_cuti', 'alasan_cuti', 'lama_cuti', 'status'
    ];

    /**
     * Relasi: Ambil data karyawan pemilik cuti ini (Many-to-One)
     */
    public function getKaryawan(int $id_karyawan)
    {
        $karyawanModel = new KaryawanModel();
        return $karyawanModel->find($id_karyawan);
    }

    /**
     * Relasi: Ambil data HRD yang memproses cuti ini (Many-to-One)
     */
    public function getHrd(int $id_hrd)
    {
        $hrdModel = new HrdModel();
        return $hrdModel->find($id_hrd);
    }

    /**
     * Ambil semua cuti beserta data karyawan dan nama HRD yang memproses (JOIN)
     */
    public function getCutiWithKaryawan()
    {
        return $this->select('cuti.*, karyawan.nik, karyawan.nama as nama_karyawan, karyawan.divisi, hrd.hrd as nama_hrd')
                    ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                    ->join('hrd', 'hrd.id_hrd = cuti.id_hrd', 'left') // Left join agar pengajuan pending tetap tampil
                    ->findAll();
    }

    /**
     * Ambil cuti berdasarkan karyawan tertentu
     */
    public function getCutiByKaryawan(int $id_karyawan)
    {
        return $this->where('id_karyawan', $id_karyawan)->findAll();
    }

    /**
     * Ambil cuti berdasarkan status tertentu
     */
    public function getCutiByStatus(string $status)
    {
        return $this->select('cuti.*, karyawan.nik, karyawan.nama as nama_karyawan')
                    ->join('karyawan', 'karyawan.id_karyawan = cuti.id_karyawan')
                    ->where('cuti.status', $status)
                    ->findAll();
    }
}