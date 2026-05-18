<?php
// Memaksa browser untuk melakukan unduh file langsung ke format .xls
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Absensi_PT_Sari_Kresna_Kimia_" . str_replace(' ', '_', $periode_teks) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="9" style="font-size: 14px; font-weight: bold; text-align: center; border: none;">PT SARI KRESNA KIMIA</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 12px; font-weight: bold; text-align: center; border: none;">Laporan Rekapitulasi Kehadiran & Performa Karyawan</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 11px; text-align: center; font-style: italic; border: none;">Periode Rekap: <?= $periode_teks ?></th>
        </tr>
        <tr></tr> <tr style="background-color: #1e3a8a; color: white; font-weight: bold; text-align: center;">
            <th style="width: 50px;">No</th>
            <th style="width: 120px;">NIK</th>
            <th style="width: 200px;">Nama Karyawan</th>
            <th style="width: 100px;">Hadir (Hari)</th>
            <th style="width: 100px;">Terlambat (Kali)</th>
            <th style="width: 100px;">Alfa (Hari)</th>
            <th style="width: 100px;">Cuti / Izin (Hari)</th>
            <th style="width: 100px;">Sisa Jatah Cuti</th>
            <th style="width: 120px;">Skor KPI Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach($data_laporan as $row): ?>
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="font-family: monospace; text-align: center;">'<?= esc($row['nik']) ?></td>
            <td style="font-weight: bold;"><?= esc($row['nama']) ?></td>
            <td style="text-align: center; color: green; font-weight: bold;"><?= $row['hadir'] ?></td>
            <td style="text-align: center;"><?= $row['terlambat'] ?></td>
            <td style="text-align: center; color: red;"><?= $row['alfa'] ?></td>
            <td style="text-align: center;"><?= $row['cuti'] ?></td>
            <td style="text-align: center; font-weight: bold;"><?= $row['sisa_cuti'] ?></td>
            <td style="text-align: center; font-weight: bold; background-color: #f0fdf4;"><?= $row['kpi'] ?>%</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>