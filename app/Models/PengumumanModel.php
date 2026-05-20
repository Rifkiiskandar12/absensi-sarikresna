<?php
namespace App\Models;
use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $primaryKey       = 'id_pengumuman';
    protected $allowedFields    = ['judul', 'isi_pengumuman', 'tanggal_posting', 'pembuat'];
}