<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetupDatabase extends Migration
{
    public function up()
    {
        // 1. Table: hrd
        $this->forge->addField([
            'id_hrd' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'hrd' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['HRD', 'Admin', 'Pimpinan'],
                'null'       => true,
            ],
            'status_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_hrd', true);
        $this->forge->createTable('hrd');

        // 2. Table: karyawan
        $this->forge->addField([
            'id_karyawan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'divisi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Karyawan',
            ],
            'jam_masuk_shift' => [
                'type'    => 'TIME',
                'default' => '08:00:00',
            ],
            'jam_pulang_shift' => [
                'type'    => 'TIME',
                'default' => '17:00:00',
            ],
            'status_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_karyawan', true);
        $this->forge->createTable('karyawan');

        // 3. Table: absensi
        $this->forge->addField([
            'id_absensi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_karyawan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'foto_masuk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'lokasi_masuk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'jam_keluar' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'foto_keluar' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'lokasi_keluar' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'hari' => [
                'type'       => 'INT',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_absensi', true);
        $this->forge->createTable('absensi');

        // 4. Table: cuti
        $this->forge->addField([
            'id_cuti' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_karyawan' => [
                'type'       => 'INT',
                'constraint' => 50,
                'null'       => true,
            ],
            'tanggal_pengajuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tanggal_diterima' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tanggal_mulai_cuti' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_selesai_cuti' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'alasan_cuti' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'lama_cuti' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Pending',
            ],
        ]);
        $this->forge->addKey('id_cuti', true);
        $this->forge->createTable('cuti');

        // 5. Table: pengumuman
        $this->forge->addField([
            'id_pengumuman' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'isi_pengumuman' => [
                'type' => 'TEXT',
            ],
            'tanggal_posting' => [
                'type'    => 'DATETIME',
                'null'    => true,
                // In CI4 Migration, current_timestamp can be tricky depending on DB, 
                // but let's stick to the SQL structure.
            ],
            'pembuat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id_pengumuman', true);
        $this->forge->createTable('pengumuman');
    }

    public function down()
    {
        $this->forge->dropTable('absensi');
        $this->forge->dropTable('cuti');
        $this->forge->dropTable('hrd');
        $this->forge->dropTable('karyawan');
        $this->forge->dropTable('pengumuman');
    }
}
