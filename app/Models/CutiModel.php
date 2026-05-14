<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiModel extends Model
{
    protected $table            = 'cuti';
    protected $primaryKey       = 'id_cuti';
    protected $allowedFields    = [
        'id_karyawan', 'tanggal_pengajuan', 'tanggal_diterima', 
        'tanggal_mulai_cuti', 'tanggal_selesai_cuti', 'alasan_cuti', 'lama_cuti', 'status'
    ];
}