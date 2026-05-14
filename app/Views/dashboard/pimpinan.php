<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pimpinan - PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans">

    <nav class="bg-slate-900 text-white p-4 shadow-md flex justify-between items-center border-b-4 border-amber-500">
        <div class="font-bold text-xl tracking-wide flex items-center gap-2">
            <span>PT Sari Kresna Kimia</span>
            <span class="text-[10px] bg-amber-500 text-slate-900 px-2 py-0.5 rounded-full uppercase">Executive Panel</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-sm">Halo, <?= esc($username); ?></span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-slate-700 hover:bg-red-600 px-4 py-2 rounded text-xs font-bold transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 mt-6">
        
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Ringkasan Kehadiran</h1>
                <p class="text-slate-500 text-sm mt-1">Data real-time untuk hari ini: <span class="font-bold text-slate-700"><?= date('d F Y') ?></span></p>
            </div>
            
            <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition flex items-center gap-2 shadow-md">
                🖨️ Cetak Laporan Hari Ini
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Karyawan</p>
                    <h3 class="text-4xl font-black text-slate-700"><?= $total_karyawan; ?></h3>
                </div>
                <div class="text-4xl bg-blue-50 p-3 rounded-full">👥</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadir Hari Ini</p>
                    <h3 class="text-4xl font-black text-emerald-600"><?= $hadir_hari_ini; ?></h3>
                </div>
                <div class="text-4xl bg-emerald-50 p-3 rounded-full">✅</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sedang Cuti</p>
                    <h3 class="text-4xl font-black text-amber-500"><?= $cuti_hari_ini; ?></h3>
                </div>
                <div class="text-4xl bg-amber-50 p-3 rounded-full">🏖️</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="font-bold text-lg text-slate-700 mb-4">Detail Kehadiran Karyawan</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <th class="p-4 border-b">NIK</th>
                            <th class="p-4 border-b">Nama Karyawan</th>
                            <th class="p-4 border-b">Waktu Masuk</th>
                            <th class="p-4 border-b">Waktu Pulang</th>
                            <th class="p-4 border-b text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($rekap_absensi)): ?>
                            <tr><td colspan="5" class="p-8 text-center text-slate-400 italic">Belum ada data kehadiran yang masuk hari ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($rekap_absensi as $row): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-slate-500 font-medium text-xs"><?= esc($row['nik']); ?></td>
                                <td class="p-4 font-bold text-slate-700"><?= esc($row['nama']); ?></td>
                                <td class="p-4 text-emerald-600 font-bold"><?= $row['jam_masuk']; ?></td>
                                <td class="p-4 text-amber-600 font-bold"><?= $row['jam_keluar'] ?? '--:--:--'; ?></td>
                                <td class="p-4 text-center">
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold">Hadir</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <style>
        @media print {
            nav, button { display: none !important; }
            body { background-color: white !important; }
            .shadow-sm, .shadow-md { box-shadow: none !important; }
        }
    </style>
</body>
</html>