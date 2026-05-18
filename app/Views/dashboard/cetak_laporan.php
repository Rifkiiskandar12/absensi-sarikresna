<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran - <?= $periode_teks ?></title>
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
                <span class="px-3 py-1 bg-slate-900 text-white font-bold text-xs uppercase tracking-widest rounded-sm">Laporan Internal</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-xl font-bold uppercase underline underline-offset-4 mb-2">Rekapitulasi Kehadiran & Cuti Karyawan</h2>
            <p class="font-bold text-slate-600">Periode: <?= $periode_teks ?></p>
        </div>

        <table class="w-full border-collapse border border-slate-800 text-sm mb-12">
            <thead>
                <tr class="bg-slate-200">
                    <th class="border border-slate-800 py-3 px-2 text-center w-10">No</th>
                    <th class="border border-slate-800 py-3 px-2 text-left">NIK & Nama</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Hadir</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Telat</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Alfa</th>
                    <th class="border border-slate-800 py-3 px-2 text-center bg-amber-100">Cuti/Izin</th>
                    <th class="border border-slate-800 py-3 px-2 text-center bg-amber-100">Sisa Cuti</th>
                    <th class="border border-slate-800 py-3 px-2 text-center">Skor KPI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_laporan as $row): ?>
                <tr>
                    <td class="border border-slate-800 py-2 px-2 text-center"><?= $no++ ?></td>
                    <td class="border border-slate-800 py-2 px-2">
                        <div class="font-bold"><?= esc($row['nama']) ?></div>
                        <div class="font-mono text-[10px] text-slate-500"><?= esc($row['nik']) ?></div>
                    </td>
                    <td class="border border-slate-800 py-2 px-2 text-center font-bold text-green-700"><?= $row['hadir'] ?> Hari</td>
                    <td class="border border-slate-800 py-2 px-2 text-center"><?= $row['terlambat'] ?>x</td>
                    <td class="border border-slate-800 py-2 px-2 text-center <?= $row['alfa'] > 0 ? 'text-red-600 font-bold' : '' ?>"><?= $row['alfa'] ?> Hari</td>
                    <td class="border border-slate-800 py-2 px-2 text-center bg-amber-50"><?= $row['cuti'] ?> Hari</td>
                    <td class="border border-slate-800 py-2 px-2 text-center bg-amber-50 font-bold <?= $row['sisa_cuti'] <= 2 ? 'text-red-600' : 'text-blue-600' ?>"><?= $row['sisa_cuti'] ?></td>
                    <td class="border border-slate-800 py-2 px-2 text-center font-black <?= $row['kpi'] >= 80 ? 'text-green-700' : 'text-red-600' ?>"><?= $row['kpi'] ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="flex justify-end mt-12">
            <div class="text-center">
                <p class="text-sm font-medium mb-16">Ditetapkan & Dicetak pada: <br> <b><?= date('d F Y') ?></b></p>
                <p class="font-bold underline underline-offset-4 uppercase"><?= esc($nama_pencetak) ?></p>
                <p class="text-sm text-slate-600">
                    <?= $role_pencetak == 'Pimpinan' ? 'Pimpinan / Direktur' : 'Human Resource Department' ?>
                </p>
            </div>
        </div>

        <button onclick="window.print()" class="no-print mt-10 w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-blue-700">Print Laporan Kembali</button>

    </div>
</body>
</html>