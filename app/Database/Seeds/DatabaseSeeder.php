<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Data for HRD
        $dataHrd = [
            ['id_hrd' => 1, 'hrd' => 'Pak Gunawan', 'username' => 'admin', 'password' => 'admin123', 'role' => 'Admin', 'status_aktif' => 1],
            ['id_hrd' => 2, 'hrd' => 'Bu Rina (HRD)', 'username' => 'hrd', 'password' => 'hrd123', 'role' => 'HRD', 'status_aktif' => 1],
            ['id_hrd' => 3, 'hrd' => 'Bapak Direktur', 'username' => 'pimpinan', 'password' => 'pimpinan123', 'role' => 'Pimpinan', 'status_aktif' => 1],
        ];

        // 2. Data for Karyawan
        $dataKaryawan = [
            ['id_karyawan' => 1, 'nik' => 'KRY-001', 'nama' => 'Muhammad Daffa', 'username' => 'daffa', 'password' => 'daffa123', 'divisi' => 'Keuangan & Tax', 'jam_masuk_shift' => '13:00:00', 'jam_pulang_shift' => '21:00:00', 'status_aktif' => 1],
            ['id_karyawan' => 2, 'nik' => 'KRY-002', 'nama' => 'Siti Aminah', 'username' => 'siti', 'password' => 'siti123', 'divisi' => 'Karyawan', 'jam_masuk_shift' => '08:00:00', 'jam_pulang_shift' => '17:00:00', 'status_aktif' => 1],
            ['id_karyawan' => 3, 'nik' => 'KRY-003', 'nama' => 'Rifki', 'username' => 'rifki', 'password' => 'rifki123', 'divisi' => 'Karyawan', 'jam_masuk_shift' => '08:00:00', 'jam_pulang_shift' => '17:00:00', 'status_aktif' => 1],
            ['id_karyawan' => 4, 'nik' => '12345678', 'nama' => 'testing1', 'username' => 'test', 'password' => 'test123', 'divisi' => 'Karyawan', 'jam_masuk_shift' => '08:00:00', 'jam_pulang_shift' => '17:00:00', 'status_aktif' => 1],
            ['id_karyawan' => 5, 'nik' => 'test', 'nama' => 'testing2', 'username' => 'testadmin', 'password' => 'admin123', 'divisi' => 'Admin', 'jam_masuk_shift' => '08:00:00', 'jam_pulang_shift' => '17:00:00', 'status_aktif' => 1],
        ];

        // 3. Data for Absensi
        $dataAbsensi = [
            ['id_absensi' => 1, 'id_karyawan' => '1', 'jam_masuk' => '00:59:41', 'foto_masuk' => 'masuk_1_1778720381.jpg', 'lokasi_masuk' => '-7.899763452907175,112.6065910051003', 'jam_keluar' => '01:23:07', 'foto_keluar' => 'keluar_1_1778721787.jpg', 'lokasi_keluar' => '-7.899772,112.606606', 'tanggal' => '2026-05-14', 'hari' => 4],
            ['id_absensi' => 2, 'id_karyawan' => '1', 'jam_masuk' => '01:03:00', 'foto_masuk' => 'masuk_1_1778720580.jpg', 'lokasi_masuk' => '-7.899772,112.606606', 'jam_keluar' => null, 'foto_keluar' => null, 'lokasi_keluar' => null, 'tanggal' => '2026-05-14', 'hari' => 4],
            ['id_absensi' => 3, 'id_karyawan' => '3', 'jam_masuk' => '02:00:44', 'foto_masuk' => 'masuk_3_1778724044.jpg', 'lokasi_masuk' => '-7.899772,112.606606', 'jam_keluar' => '02:00:52', 'foto_keluar' => 'keluar_3_1778724052.jpg', 'lokasi_keluar' => '-7.899772,112.606606', 'tanggal' => '2026-05-14', 'hari' => 4],
            ['id_absensi' => 4, 'id_karyawan' => '4', 'jam_masuk' => '07:27:40', 'foto_masuk' => 'masuk_4_1778743660.jpg', 'lokasi_masuk' => '-7.899772,112.606606', 'jam_keluar' => '07:27:55', 'foto_keluar' => 'keluar_4_1778743675.jpg', 'lokasi_keluar' => '-7.899772,112.606606', 'tanggal' => '2026-05-14', 'hari' => 4],
            ['id_absensi' => 5, 'id_karyawan' => '3', 'jam_masuk' => '05:12:13', 'foto_masuk' => 'masuk_3_1779253933.jpg', 'lokasi_masuk' => '-7.9731858603001635,112.60865975549959', 'jam_keluar' => null, 'foto_keluar' => null, 'lokasi_keluar' => null, 'tanggal' => '2026-05-20', 'hari' => 3],
            ['id_absensi' => 6, 'id_karyawan' => '1', 'jam_masuk' => '06:05:17', 'foto_masuk' => 'masuk_1_1779257117.jpg', 'lokasi_masuk' => '-7.973183344143236,112.60865635218272', 'jam_keluar' => '06:05:28', 'foto_keluar' => 'keluar_1_1779257128.jpg', 'lokasi_keluar' => '-7.973174349065289,112.60865820842845', 'tanggal' => '2026-05-20', 'hari' => 3],
        ];

        // 4. Data for Cuti
        $dataCuti = [
            ['id_cuti' => 1, 'id_karyawan' => 1, 'tanggal_pengajuan' => '2026-05-14', 'tanggal_diterima' => null, 'tanggal_mulai_cuti' => '2026-05-18', 'tanggal_selesai_cuti' => '2026-05-20', 'alasan_cuti' => 'Males aja', 'lama_cuti' => 3, 'status' => 'Diterima'],
            ['id_cuti' => 2, 'id_karyawan' => 1, 'tanggal_pengajuan' => '2026-05-14', 'tanggal_diterima' => null, 'tanggal_mulai_cuti' => '2026-05-18', 'tanggal_selesai_cuti' => '2026-05-20', 'alasan_cuti' => 'Males aja', 'lama_cuti' => 3, 'status' => 'Ditolak'],
            ['id_cuti' => 3, 'id_karyawan' => 1, 'tanggal_pengajuan' => '2026-05-14', 'tanggal_diterima' => null, 'tanggal_mulai_cuti' => '2026-05-28', 'tanggal_selesai_cuti' => '2026-05-29', 'alasan_cuti' => 'p', 'lama_cuti' => 2, 'status' => 'Ditolak'],
        ];

        // 5. Data for Pengumuman
        $dataPengumuman = [
            ['id_pengumuman' => 1, 'judul' => 'HALOOOOOOOO', 'isi_pengumuman' => 'testing bang', 'tanggal_posting' => '2026-05-20 05:45:35', 'pembuat' => 'pimpinan'],
        ];

        // Execute inserts
        $this->db->table('hrd')->insertBatch($dataHrd);
        $this->db->table('karyawan')->insertBatch($dataKaryawan);
        $this->db->table('absensi')->insertBatch($dataAbsensi);
        $this->db->table('cuti')->insertBatch($dataCuti);
        $this->db->table('pengumuman')->insertBatch($dataPengumuman);
    }
}
