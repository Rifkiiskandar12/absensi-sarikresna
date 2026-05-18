<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeys extends Migration
{
    public function up()
    {
        // ============================================================
        // STEP 1: Pastikan semua tabel menggunakan InnoDB engine
        //         (Foreign Key hanya bisa di InnoDB)
        // ============================================================
        $this->db->query('ALTER TABLE `karyawan` ENGINE = InnoDB');
        $this->db->query('ALTER TABLE `absensi` ENGINE = InnoDB');
        $this->db->query('ALTER TABLE `cuti` ENGINE = InnoDB');
        $this->db->query('ALTER TABLE `hrd` ENGINE = InnoDB');

        // ============================================================
        // STEP 2: Pastiin kolom id_karyawan di karyawan, absensi, cuti
        //         bertipe INT(11) UNSIGNED supaya kompatibel untuk FK
        // ============================================================
        $this->db->query('ALTER TABLE `karyawan` MODIFY `id_karyawan` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT');
        $this->db->query('ALTER TABLE `absensi` MODIFY `id_karyawan` INT(11) UNSIGNED NOT NULL');
        $this->db->query('ALTER TABLE `cuti` MODIFY `id_karyawan` INT(11) UNSIGNED NOT NULL');

        // ============================================================
        // STEP 3: Tambahkan INDEX pada kolom FK (jika belum ada)
        // ============================================================
        // Cek apakah index sudah ada sebelum menambahkan
        $absensiIndexes = $this->db->query("SHOW INDEX FROM `absensi` WHERE Column_name = 'id_karyawan'")->getResultArray();
        if (empty($absensiIndexes)) {
            $this->db->query('ALTER TABLE `absensi` ADD INDEX `idx_absensi_karyawan` (`id_karyawan`)');
        }

        $cutiIndexes = $this->db->query("SHOW INDEX FROM `cuti` WHERE Column_name = 'id_karyawan'")->getResultArray();
        if (empty($cutiIndexes)) {
            $this->db->query('ALTER TABLE `cuti` ADD INDEX `idx_cuti_karyawan` (`id_karyawan`)');
        }

        // ============================================================
        // STEP 4: Tambahkan FOREIGN KEY constraints
        // 
        // absensi.id_karyawan → karyawan.id_karyawan
        // cuti.id_karyawan    → karyawan.id_karyawan
        //
        // ON DELETE CASCADE = Jika karyawan dihapus, data terkait ikut hilang
        // ON UPDATE CASCADE = Jika id_karyawan berubah, FK ikut update
        // ============================================================
        $this->db->query('
            ALTER TABLE `absensi`
            ADD CONSTRAINT `fk_absensi_karyawan`
            FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ');

        $this->db->query('
            ALTER TABLE `cuti`
            ADD CONSTRAINT `fk_cuti_karyawan`
            FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id_karyawan`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        // Hapus foreign key constraints (urutan: child dulu)
        $this->db->query('ALTER TABLE `absensi` DROP FOREIGN KEY `fk_absensi_karyawan`');
        $this->db->query('ALTER TABLE `cuti` DROP FOREIGN KEY `fk_cuti_karyawan`');
    }
}
