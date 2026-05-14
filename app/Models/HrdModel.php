<?php

namespace App\Models;

use CodeIgniter\Model;

class HrdModel extends Model
{
    protected $table            = 'hrd';
    protected $primaryKey       = 'id_hrd';
    protected $allowedFields    = ['hrd', 'username', 'password', 'role', 'status_aktif'];
}