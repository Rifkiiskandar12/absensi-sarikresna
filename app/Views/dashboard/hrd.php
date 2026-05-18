<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD - PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans relative">

    <nav class="bg-blue-800 text-white p-4 shadow-md flex justify-between items-center">
        <div class="font-bold text-xl tracking-wide">Panel HRD - Sari Kresna</div>
        <div class="flex items-center gap-4">
            <span class="font-medium">Halo, <?= esc($username); ?> (<?= esc($role); ?>)</span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm font-bold transition">Logout</a>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-600">
                    <h2 class="font-bold text-xl text-slate-800 mb-4">Tambah Karyawan Baru</h2>
                    <form action="<?= base_url('hrd/simpan_karyawan') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">NIK</label>
                            <input type="text" name="nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Contoh: KRY-004" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Nama Karyawan" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Divisi / Jabatan</label>
                            <select name="divisi" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                                <option value="Karyawan">Karyawan</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Username Login</label>
                            <input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password Sementara</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                            Simpan Data Karyawan
                        </button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-emerald-500 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-lg text-slate-800">KPI Kehadiran Bulanan</h2>
                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider"><?= date('F Y') ?></span>
                    </div>
                    <div class="space-y-4">
                        <?php if(empty($kpi_karyawan)): ?>
                            <p class="text-sm text-slate-400 italic text-center py-4">Belum ada data metrik kehadiran.</p>
                        <?php else: ?>
                            <?php foreach($kpi_karyawan as $kpi): ?>
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span><?= esc($kpi['nama']) ?></span>
                                    <span><?= $kpi['hadir'] ?>/22 Hari (<?= $kpi['kpi'] ?>%)</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                    <?php 
                                        $warna_bar = $kpi['kpi'] >= 80 ? 'bg-emerald-500' : ($kpi['kpi'] >= 50 ? 'bg-amber-500' : 'bg-red-500');
                                    ?>
                                    <div class="<?= $warna_bar ?> h-full rounded-full transition-all duration-500" style="width: <?= $kpi['kpi'] ?>%"></div>
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
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full"><?= date('d F Y'); ?></span>
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
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada aktivitas absensi hari ini.</td>
                                    </tr>
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
                                                <button onclick="openFoto('<?= base_url('uploads/absensi/' . $row['foto_masuk']); ?>')" class="text-[10px] text-indigo-500 hover:text-indigo-700 font-semibold flex items-center gap-1 mt-1">
                                                    📸 Lihat Selfie
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3">
                                            <div class="text-orange-600 font-bold"><?= $row['jam_keluar'] ?? '--:--:--'; ?></div>
                                            <?php if($row['foto_keluar']): ?>
                                                <button onclick="openFoto('<?= base_url('uploads/absensi/' . $row['foto_keluar']); ?>')" class="text-[10px] text-indigo-500 hover:text-indigo-700 font-semibold flex items-center gap-1 mt-1">
                                                    📸 Lihat Selfie
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3 text-center">
                                            <?php if($row['lokasi_masuk']): ?>
                                                <button onclick="openMap('<?= $row['lokasi_masuk']; ?>')" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                                    📍 Cek Maps
                                                </button>
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
                                        <td class="p-3 border-b">
                                            <div class="font-bold text-slate-700"><?= esc($c['nama']); ?></div>
                                            <div class="text-[10px] text-slate-500"><?= $c['lama_cuti']; ?> Hari</div>
                                        </td>
                                        <td class="p-3 border-b text-xs font-medium"><?= $c['tanggal_mulai_cuti']; ?></td>
                                        <td class="p-3 border-b text-[11px] italic text-slate-600">"<?= esc($c['alasan_cuti']); ?>"</td>
                                        <td class="p-3 border-b text-center">
                                            <div class="flex gap-1 justify-center">
                                                <a href="<?= base_url('cuti/setuju/' . $c['id_cuti']); ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] font-bold transition shadow-sm">ACC</a>
                                                <a href="<?= base_url('cuti/tolak/' . $c['id_cuti']); ?>" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-[10px] font-bold transition shadow-sm">TOLAK</a>
                                            </div>
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
            <div class="flex justify-between items-center p-4 border-b border-slate-100">
                <h3 id="modalTitle" class="font-bold text-lg text-slate-800">Detail</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition focus:outline-none font-bold">X</button>
            </div>
            <div class="p-4 flex justify-center bg-slate-100/50" id="modalBody">
                <img id="modalImg" src="" class="max-h-[60vh] object-contain rounded-lg shadow-sm hidden">
                <iframe id="modalIframe" src="" class="w-full h-80 rounded-lg shadow-sm hidden" frameborder="0"></iframe>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button onclick="closeModal()" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-semibold hover:bg-slate-300 transition text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalOverlay');
        const modalTitle = document.getElementById('modalTitle');
        const modalImg = document.getElementById('modalImg');
        const modalIframe = document.getElementById('modalIframe');

        function openFoto(url) {
            modalTitle.innerText = "Bukti Selfie Absensi";
            modalImg.src = url;
            modalImg.classList.remove('hidden');
            modalIframe.classList.add('hidden');
            modalIframe.src = ""; 
            modal.classList.remove('hidden');
        }

        function openMap(koordinat) {
            modalTitle.innerText = "Titik Lokasi Absensi";
            modalIframe.src = `https://maps.google.com/maps?q=${koordinat}&z=15&output=embed`;
            modalIframe.classList.remove('hidden');
            modalImg.classList.add('hidden');
            modalImg.src = ""; 
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modalImg.src = "";
            modalIframe.src = "";
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    </script>
</body>
</html>