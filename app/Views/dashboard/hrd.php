<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD - PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans relative">

    <nav class="bg-blue-800 text-white p-4 shadow-md flex justify-between items-center border-b-4 border-indigo-400">
        <div class="font-bold text-xl tracking-wide flex items-center gap-2">
            <span>Panel HRD - Sari Kresna</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-sm">Halo, <?= esc($username); ?> (<?= esc($role); ?>)</span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-xs font-bold transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 mt-6">
        
        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-semibold shadow-sm italic">
                ✅ <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($peringatan_dini)): ?>
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-6 shadow-sm rounded-xl flex items-start gap-3">
                <span class="text-2xl">⚠️</span>
                <div>
                    <h3 class="font-bold">Peringatan Dini Sistem!</h3>
                    <p class="text-sm mt-1">Sistem mendeteksi karyawan berikut tidak absen dan tidak memiliki status cuti aktif selama 3 hari berturut-turut: 
                        <b class="bg-red-200 px-2 py-0.5 rounded"><?= implode(', ', $peringatan_dini) ?></b>. Harap segera lakukan tindak lanjut.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Ruang Kontrol HRD</h1>
                <p class="text-slate-500 text-sm mt-1">Manajemen operasional karyawan | <span class="font-bold text-slate-700"><?= date('d F Y') ?></span></p>
            </div>
            
            <form action="<?= base_url('dashboard/cetak_laporan') ?>" method="POST" target="_blank" class="flex items-center gap-2 bg-white p-2 rounded-xl shadow-sm border border-slate-200">
                <?= csrf_field() ?>
                <select name="bulan" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 font-semibold" required>
                    <?php 
                        $bulan_sekarang = date('m');
                        $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                        foreach($nama_bulan as $num => $nama): 
                    ?>
                        <option value="<?= $num ?>" <?= $bulan_sekarang == $num ? 'selected' : '' ?>><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="tahun" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 font-semibold" required>
                    <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                </select>
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                    🖨️ Cetak PDF
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-600 mb-6">
                    <h2 class="font-bold text-xl text-slate-800 mb-4">Tambah Karyawan Baru</h2>
                    <form action="<?= base_url('hrd/simpan_karyawan') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="text" name="nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="NIK (Contoh: KRY-004)" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="nama" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <select name="divisi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="Karyawan">Divisi Operasional (Karyawan)</option>
                                <option value="Admin">Admin Sistem</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="Username Login" required>
                        </div>
                        <div class="mb-5">
                            <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="Password Sementara" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">Simpan Data</button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-emerald-500">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-slate-800">Statistik Karyawan</h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= date('M Y') ?></span>
                    </div>
                    <div class="space-y-4">
                        <?php if(empty($statistik_karyawan)): ?>
                            <p class="text-sm text-slate-400 italic text-center py-4">Belum ada data metrik kehadiran.</p>
                        <?php else: ?>
                            <?php foreach($statistik_karyawan as $k): ?>
                            <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                                    <span><?= esc($k['nama']) ?></span>
                                    <span class="<?= $k['sisa_cuti'] <= 2 ? 'text-red-500' : 'text-blue-600' ?>">Cuti: <?= $k['sisa_cuti'] ?></span>
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                                    <div class="flex-1 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <?php $warna_bar = $k['kpi'] >= 80 ? 'bg-emerald-500' : ($k['kpi'] >= 50 ? 'bg-amber-500' : 'bg-red-500'); ?>
                                        <div class="<?= $warna_bar ?> h-full rounded-full" style="width: <?= $k['kpi'] ?>%"></div>
                                    </div>
                                    <span>KPI: <?= $k['kpi'] ?>%</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-lg text-slate-700">Monitoring Absensi Hari Ini</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                    <th class="p-3 border-b">Karyawan</th>
                                    <th class="p-3 border-b">Waktu Masuk</th>
                                    <th class="p-3 border-b">Waktu Pulang</th>
                                    <th class="p-3 border-b text-center">Detail Lokasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(empty($daftar_absensi)): ?>
                                    <tr><td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada aktivitas absensi hari ini.</td></tr>
                                <?php else: ?>
                                    <?php foreach($daftar_absensi as $row): ?>
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="p-3">
                                            <div class="font-bold text-slate-700"><?= esc($row['nama']); ?></div>
                                            <div class="text-[11px] text-slate-500 uppercase tracking-tighter"><?= esc($row['nik']); ?></div>
                                        </td>
                                        <td class="p-3">
                                            <div class="text-blue-600 font-bold"><?= $row['jam_masuk']; ?></div>
                                            <?php if($row['foto_masuk']): ?>
                                                <button onclick="openFoto('<?= base_url('uploads/absensi/' . $row['foto_masuk']); ?>')" class="text-[10px] text-indigo-500 hover:underline flex mt-1">Lihat Selfie</button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3">
                                            <div class="text-orange-600 font-bold"><?= $row['jam_keluar'] ?? '--:--:--'; ?></div>
                                            <?php if($row['foto_keluar']): ?>
                                                <button onclick="openFoto('<?= base_url('uploads/absensi/' . $row['foto_keluar']); ?>')" class="text-[10px] text-indigo-500 hover:underline flex mt-1">Lihat Selfie</button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3 text-center">
                                            <?php if($row['lokasi_masuk']): ?>
                                                <button onclick="openMap('<?= $row['lokasi_masuk']; ?>')" class="bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-bold transition hover:bg-slate-200">📍 Cek Maps</button>
                                            <?php else: ?>
                                                <span class="text-slate-300 text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
                    <h2 class="font-bold text-lg text-slate-700 mb-4">Permintaan Cuti Menunggu ACC</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold">
                                    <th class="p-3 border-b">Karyawan</th>
                                    <th class="p-3 border-b">Tgl Cuti</th>
                                    <th class="p-3 border-b">Alasan</th>
                                    <th class="p-3 border-b text-center">Keputusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($daftar_cuti)): ?>
                                    <tr><td colspan="4" class="p-6 text-center text-slate-400 italic">Tidak ada permintaan cuti pending.</td></tr>
                                <?php else: ?>
                                    <?php foreach($daftar_cuti as $c): ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-3 border-b"><div class="font-bold text-slate-700"><?= esc($c['nama']); ?></div><div class="text-[10px] text-slate-500"><?= $c['lama_cuti']; ?> Hari</div></td>
                                        <td class="p-3 border-b text-xs font-medium"><?= $c['tanggal_mulai_cuti']; ?></td>
                                        <td class="p-3 border-b text-[11px] italic text-slate-600">"<?= esc($c['alasan_cuti']); ?>"</td>
                                        <td class="p-3 border-b text-center flex gap-1 justify-center">
                                            <a href="<?= base_url('cuti/setuju/' . $c['id_cuti']); ?>" class="bg-green-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow-sm">ACC</a>
                                            <a href="<?= base_url('cuti/tolak/' . $c['id_cuti']); ?>" class="bg-red-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow-sm">TOLAK</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="modalOverlay" class="fixed inset-0 bg-slate-900/80 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden relative">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modalTitle" class="font-bold text-lg text-slate-800">Detail</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 font-bold">X</button>
            </div>
            <div class="p-4 flex justify-center bg-slate-100" id="modalBody">
                <img id="modalImg" src="" class="max-h-[60vh] object-contain rounded-lg hidden">
                <iframe id="modalIframe" src="" class="w-full h-80 rounded-lg hidden" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalOverlay');
        const modalTitle = document.getElementById('modalTitle');
        const modalImg = document.getElementById('modalImg');
        const modalIframe = document.getElementById('modalIframe');

        function openFoto(url) { modalTitle.innerText = "Selfie Absensi"; modalImg.src = url; modalImg.classList.remove('hidden'); modalIframe.classList.add('hidden'); modal.classList.remove('hidden'); }
        function openMap(koordinat) { modalTitle.innerText = "Lokasi"; modalIframe.src = `https://maps.google.com/maps?q=${koordinat}&z=15&output=embed`; modalIframe.classList.remove('hidden'); modalImg.classList.add('hidden'); modal.classList.remove('hidden'); }
        function closeModal() { modal.classList.add('hidden'); modalImg.src = ""; modalIframe.src = ""; }
    </script>
</body>
</html>