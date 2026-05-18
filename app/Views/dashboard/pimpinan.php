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
        
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-8 gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Ruang Kendali Direksi</h1>
                <p class="text-slate-500 text-xs mt-0.5">Mata memantau tanggal: <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded text-sm"><?= date('d F Y', strtotime($tanggal_filter)) ?></span></p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3">
                <form action="<?= base_url('dashboard/cetak_laporan_harian') ?>" method="POST" target="_blank" class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                    <?= csrf_field() ?>
                    <span class="text-[11px] font-black uppercase text-slate-400 pl-1">Harian:</span>
                    <input type="date" name="tanggal" value="<?= $tanggal_filter ?>" class="bg-white border text-xs rounded-lg px-2 py-1.5 font-bold text-slate-700 focus:outline-none" required>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">🖨️ Cetak</button>
                </form>

                <form action="<?= base_url('dashboard/cetak_laporan') ?>" method="POST" target="_blank" class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                    <?= csrf_field() ?>
                    <span class="text-[11px] font-black uppercase text-slate-400 pl-1">Bulanan:</span>
                    <select name="bulan" class="bg-white border text-xs rounded-lg px-2 py-1.5 font-bold text-slate-700" required>
                        <?php 
                            $bulan_sekarang = date('m');
                            $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                            foreach($nama_bulan as $num => $nama): 
                        ?>
                            <option value="<?= $num ?>" <?= $bulan_sekarang == $num ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="tahun" class="bg-white border text-xs rounded-lg px-2 py-1.5 font-bold text-slate-700" required>
                        <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                    </select>
                    
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">🖨️ PDF</button>
                    <button type="submit" formaction="<?= base_url('dashboard/export_excel') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">📊 Excel</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Karyawan</p><h3 class="text-4xl font-black text-slate-700"><?= $total_karyawan; ?></h3></div>
                <div class="text-4xl bg-blue-50 p-3 rounded-full">👥</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500 flex justify-between items-center">
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadir (Filter)</p><h3 class="text-4xl font-black text-emerald-600"><?= $hadir_hari_ini; ?></h3></div>
                <div class="text-4xl bg-emerald-50 p-3 rounded-full">✅</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500 flex justify-between items-center">
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Cuti (Filter)</p><h3 class="text-4xl font-black text-amber-500"><?= $cuti_hari_ini; ?></h3></div>
                <div class="text-4xl bg-amber-50 p-3 rounded-full">🏖️</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <h2 class="font-bold text-lg text-slate-700">Detail Kehadiran Harian</h2>
                <form method="GET" action="<?= base_url('dashboard') ?>" class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                    <label class="text-xs font-bold text-slate-500 ml-2">Intip Tanggal Lain:</label>
                    <input type="date" name="tanggal" value="<?= $tanggal_filter ?>" onchange="this.form.submit()" class="bg-white text-sm border border-slate-200 rounded px-2 py-1 font-semibold text-slate-700 cursor-pointer">
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <th class="p-4 border-b">NIK</th>
                            <th class="p-4 border-b">Nama Karyawan</th>
                            <th class="p-4 border-b">Waktu Masuk</th>
                            <th class="p-4 border-b">Waktu Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($rekap_absensi)): ?>
                            <tr><td colspan="4" class="p-8 text-center text-slate-400 italic">Tidak ada data kehadiran pada tanggal <?= date('d M Y', strtotime($tanggal_filter)) ?>.</td></tr>
                        <?php else: ?>
                            <?php foreach($rekap_absensi as $row): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-slate-500 font-medium text-xs"><?= esc($row['nik']); ?></td>
                                <td class="p-4 font-bold text-slate-700"><?= esc($row['nama']); ?></td>
                                <td class="p-4 text-emerald-600 font-bold"><?= $row['jam_masuk']; ?></td>
                                <td class="p-4 text-amber-600 font-bold"><?= $row['jam_keluar'] ?? '--:--:--'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>