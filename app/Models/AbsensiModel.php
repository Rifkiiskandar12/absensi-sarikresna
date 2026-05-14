<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id_absensi';
    protected $allowedFields    = [
        'id_karyawan', 
        'jam_masuk', 'foto_masuk', 'lokasi_masuk', 
        'jam_keluar', 'foto_keluar', 'lokasi_keluar', 
        'tanggal', 'hari'
    ];
}