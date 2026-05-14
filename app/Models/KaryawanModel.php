<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table            = 'karyawan';
    protected $primaryKey       = 'id_karyawan';
    protected $allowedFields    = ['nik', 'nama', 'username', 'password', 'divisi', 'status_aktif'];
}