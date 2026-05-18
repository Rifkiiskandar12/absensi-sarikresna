<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHrdToCutiRelation extends Migration
{
    public function up()
    {
        // 1. Ubah primary key hrd.id_hrd menjadi UNSIGNED agar sama dengan FK lainnya
        $this->db->query('ALTER TABLE `hrd` MODIFY `id_hrd` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT');

        // 2. Cek apakah kolom id_hrd di cuti sudah ada (karena migrasi sebelumnya terputus di tengah jalan)
        $columnCheck = $this->db->query("SHOW COLUMNS FROM `cuti` WHERE Field = 'id_hrd'")->getRow();
        if (!$columnCheck) {
            // Jika belum ada, buat kolomnya
            $this->db->query('ALTER TABLE `cuti` ADD `id_hrd` INT(11) UNSIGNED NULL AFTER `id_karyawan`');
        } else {
            // Jika sudah ada, pastikan tipe datanya adalah INT(11) UNSIGNED
            $this->db->query('ALTER TABLE `cuti` MODIFY `id_hrd` INT(11) UNSIGNED NULL');
        }

        // 3. Tambah index pada kolom id_hrd di cuti jika belum ada
        $indexCheck = $this->db->query("SHOW INDEX FROM `cuti` WHERE Key_name = 'idx_cuti_hrd'")->getResultArray();
        if (empty($indexCheck)) {
            $this->db->query('ALTER TABLE `cuti` ADD INDEX `idx_cuti_hrd` (`id_hrd`)');
        }

        // 4. Tambah Foreign Key Constraint ke tabel hrd
        // ON DELETE SET NULL -> Jika akun HRD dihapus, data cuti tetap aman (kolom id_hrd menjadi NULL)
        $this->db->query('
            ALTER TABLE `cuti`
            ADD CONSTRAINT `fk_cuti_hrd`
            FOREIGN KEY (`id_hrd`) REFERENCES `hrd`(`id_hrd`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        // 1. Hapus Foreign Key Constraint jika ada
        $this->db->query('ALTER TABLE `cuti` DROP FOREIGN KEY `fk_cuti_hrd`');

        // 2. Hapus Index jika ada
        $this->db->query('ALTER TABLE `cuti` DROP INDEX `idx_cuti_hrd`');

        // 3. Hapus Kolom id_hrd jika ada
        $this->db->query('ALTER TABLE `cuti` DROP COLUMN `id_hrd`');
    }
}
