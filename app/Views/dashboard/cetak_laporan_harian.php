<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Harian - <?= $tanggal_teks ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4; margin: 20mm; }
        body { background-color: white; color: black; -webkit-print-color-adjust: exact; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="font-sans antialiased text-slate-800" onload="window.print()">

    <div class="max-w-4xl mx-auto p-8">
        
        <div class="flex items-center justify-between border-b-4 border-slate-900 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-widest text-slate-900">PT SARI KRESNA KIMIA</h1>
                <p class="text-sm mt-1 text-slate-600 font-medium">Jl. Raya Industri Kimia No. 88, Kawasan Industri, Indonesia</p>
                <p class="text-sm text-slate-600 font-medium">Telp: (021) 1234567 | Email: hrd@sarikresna.co.id</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 bg-amber-500 text-slate-900 font-bold text-xs uppercase tracking-widest rounded-sm">Laporan Audit Direksi</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-xl font-bold uppercase underline underline-offset-4 mb-2">Laporan Log Absensi Karyawan (Harian)</h2>
            <p class="font-bold text-slate-600">Hari / Tanggal: <?= $tanggal_teks ?></p>
        </div>

        <table class="w-full border-collapse border border-slate-800 text-sm mb-12">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-slate-800 py-3 px-2 text-center w-12">No</th>
                    <th class="border border-slate-800 py-3 px-2 text-left">NIK</th>
                    <th class="border border-slate-800 py-3 px-2 text-left">Nama Karyawan</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Jam Masuk</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Jam Pulang</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($rekap_absensi)): ?>
                    <tr>
                        <td colspan="6" class="border border-slate-800 p-6 text-center text-slate-400 italic">Tidak ada aktivitas absensi yang terekam pada hari ini.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($rekap_absensi as $row): ?>
                    <tr>
                        <td class="border border-slate-800 py-2.5 px-2 text-center"><?= $no++ ?></td>
                        <td class="border border-slate-800 py-2.5 px-2 font-mono text-xs"><?= esc($row['nik']) ?></td>
                        <td class="border border-slate-800 py-2.5 px-2 font-bold text-slate-700"><?= esc($row['nama']) ?></td>
                        <td class="border border-slate-800 py-2.5 px-2 text-center text-blue-600 font-mono font-bold"><?= $row['jam_masuk'] ?></td>
                        <td class="border border-slate-800 py-2.5 px-2 text-center text-orange-600 font-mono font-bold"><?= $row['jam_keluar'] ?? '--:--:--' ?></td>
                        <td class="border border-slate-800 py-2.5 px-2 text-center font-bold text-green-700">Hadir</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="flex justify-end mt-12">
            <div class="text-center">
                <p class="text-sm font-medium mb-16">Laporan Diperiksa & Dicetak oleh: <br> <b><?= date('d F Y') ?></b></p>
                <p class="font-bold underline underline-offset-4 uppercase"><?= esc($nama_pencetak) ?></p>
                <p class="text-sm text-slate-500">Pimpinan / Direktur Utama</p>
            </div>
        </div>

        <button onclick="window.print()" class="no-print mt-10 w-full bg-slate-800 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-slate-900 transition">Print Dokumen</button>

    </div>
</body>
</html>