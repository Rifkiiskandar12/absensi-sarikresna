<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table            = 'karyawan';
    protected $primaryKey       = 'id_karyawan';
    protected $allowedFields = [
        'nik', 
        'nama', 
        'divisi', 
        'role',          // <--- TAMBAHKAN INI
        'username', 
        'password', 
        'status_aktif', 
        'jam_masuk_shift', 
        'jam_pulang_shift'
    ];
}