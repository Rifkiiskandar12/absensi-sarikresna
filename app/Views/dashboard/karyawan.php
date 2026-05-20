<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Karyawan - Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans">

    <nav class="bg-blue-700 text-white p-4 shadow-md flex justify-between items-center">
        <div class="font-bold text-xl">Sari Kresna Absensi</div>
        <div class="flex items-center gap-4">
            <span class="text-sm">Halo, <b><?= esc($username); ?></b></span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs font-bold transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6">
        <?php if(!empty($pengumuman_terbaru)): ?>
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-6 shadow-xl text-white mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-white/20 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider backdrop-blur-sm">📢 Info HRD</span>
                        <span class="text-xs text-blue-200 font-medium"><?= date('d F Y, H:i', strtotime($pengumuman_terbaru['tanggal_posting'])) ?> | Oleh: <?= esc($pengumuman_terbaru['pembuat']) ?></span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold mb-2 tracking-tight"><?= esc($pengumuman_terbaru['judul']) ?></h2>
                    <p class="text-blue-50 text-sm md:text-base leading-relaxed max-w-4xl"><?= nl2br(esc($pengumuman_terbaru['isi_pengumuman'])) ?></p>
                </div>
                <div class="absolute -right-8 -bottom-10 text-9xl opacity-10 drop-shadow-2xl select-none">🔔</div>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm font-semibold">
                <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-600">
                    <?php if (!$absen_hari_ini): ?>
                        <h2 class="font-bold text-lg mb-4">📸 Absen Masuk Hari Ini</h2>
                        <div class="aspect-video bg-slate-200 rounded-xl overflow-hidden mb-4"><video id="kamera" autoplay class="w-full h-full object-cover"></video></div>
                        <p id="status_lokasi" class="text-xs text-amber-600 mb-4 font-bold italic">📍 Mencari koordinat GPS...</p>
                        <form id="form_absen" action="<?= base_url('absensi/proses_masuk') ?>" method="POST">
                            <input type="hidden" id="foto" name="foto_masuk">
                            <input type="hidden" id="lokasi" name="lokasi_masuk">
                            <button type="button" id="btn_absen" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition">Ambil Foto & Absen Masuk</button>
                        </form>

                    <?php elseif ($absen_hari_ini['jam_keluar'] == null): ?>
                        <h2 class="font-bold text-lg mb-4">📸 Absen Pulang</h2>
                        <div class="mb-4 p-3 bg-blue-50 text-blue-700 text-sm rounded-lg italic">Anda masuk jam: <b><?= $absen_hari_ini['jam_masuk'] ?></b></div>
                        <div class="aspect-video bg-slate-200 rounded-xl overflow-hidden mb-4"><video id="kamera" autoplay class="w-full h-full object-cover"></video></div>
                        <p id="status_lokasi" class="text-xs text-amber-600 mb-4 font-bold italic">📍 Mencari koordinat GPS...</p>
                        <form id="form_absen" action="<?= base_url('absensi/proses_keluar') ?>" method="POST">
                            <input type="hidden" id="foto" name="foto_keluar">
                            <input type="hidden" id="lokasi" name="lokasi_keluar">
                            <button type="button" id="btn_absen" class="w-full bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 shadow-lg transition">Ambil Foto & Absen Pulang</button>
                        </form>

                    <?php else: ?>
                        <div class="text-center py-10">
                            <span class="text-5xl">✅</span>
                            <h2 class="font-bold text-xl mt-4">Absensi Hari Ini Selesai</h2>
                            <p class="text-slate-500 text-sm">Selamat beristirahat, sampai jumpa besok!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm">
                    <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2">🕒 5 Kehadiran Terakhir</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-slate-400 border-b"><th class="pb-3">Tanggal</th><th class="pb-3">Masuk</th><th class="pb-3">Pulang</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($riwayat_absen as $r): ?>
                                <tr>
                                    <td class="py-3 font-medium"><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                    <td class="py-3 text-blue-600 font-bold"><?= $r['jam_masuk'] ?></td>
                                    <td class="py-3 text-orange-600 font-bold"><?= $r['jam_keluar'] ?? '--' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500 mt-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-slate-700 flex items-center gap-2">📄 Status Pengajuan Cuti</h3>
                            <p class="text-[10px] text-slate-500 italic mt-1">*Permintaan cuti yang diajukan sedang dalam tinjauan atasan. Hasil akan dinotify di sini.</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-slate-400 border-b">
                                    <th class="pb-3">Tanggal Mulai</th>
                                    <th class="pb-3">Durasi</th>
                                    <th class="pb-3">Status / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(empty($riwayat_cuti)): ?>
                                    <tr><td colspan="3" class="py-4 text-center text-slate-400 italic">Belum ada data pengajuan cuti.</td></tr>
                                <?php else: ?>
                                    <?php foreach($riwayat_cuti as $c): ?>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-3 font-medium"><?= date('d M Y', strtotime($c['tanggal_mulai_cuti'])) ?></td>
                                        <td class="py-3"><?= $c['lama_cuti'] ?> Hari</td>
                                        <td class="py-3">
                                            <?php if($c['status'] == 'Pending'): ?>
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                    ⏳ Menunggu Persetujuan Atasan
                                                </span>
                                            <?php elseif($c['status'] == 'Diterima'): ?>
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    ✅ Disetujui (ACC)
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                                    ❌ Ditolak
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="space-y-6">
                
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 rounded-2xl text-white shadow-xl">
                    <p class="text-xs opacity-70 uppercase font-bold tracking-widest">Informasi Akun</p>
                    <h2 class="text-2xl font-bold mt-1"><?= esc($username) ?></h2>
                    <p class="text-sm opacity-90"><?= esc($role) ?> - PT Sari Kresna Kimia</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                    <h3 class="font-bold text-slate-800 mb-2">Jatah Cuti</h3>
                    <p class="text-3xl font-black text-green-600"><?= $sisa_cuti; ?> <span class="text-sm font-normal text-slate-400">Hari Tersisa</span></p>
                    <p class="text-[10px] text-slate-500 mt-2 italic">*Setiap pengajuan cuti otomatis diteruskan ke manajemen.</p>
                    <button onclick="document.getElementById('modalCuti').classList.remove('hidden')" class="mt-4 w-full bg-slate-100 text-slate-700 py-2 rounded-lg text-sm font-bold hover:bg-green-50 hover:text-green-700 transition">Ajukan Cuti Baru</button>
                </div>
            </div>
            
        </div>
    </div>

    <div id="modalCuti" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h2 class="font-bold text-xl mb-4 text-slate-800">Form Pengajuan Cuti</h2>
            <form action="<?= base_url('cuti/ajukan') ?>" method="POST" class="space-y-4">
                <div><label class="text-xs font-bold text-slate-500">TANGGAL MULAI</label><input type="date" name="tgl_mulai" class="w-full border p-2 rounded-lg" required></div>
                <div><label class="text-xs font-bold text-slate-500">TANGGAL SELESAI</label><input type="date" name="tgl_selesai" class="w-full border p-2 rounded-lg" required></div>
                <div><label class="text-xs font-bold text-slate-500">ALASAN</label><textarea name="alasan" class="w-full border p-2 rounded-lg" rows="3" placeholder="Sebutkan keperluan anda..." required></textarea></div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg font-bold">Kirim Pengajuan</button>
                    <button type="button" onclick="document.getElementById('modalCuti').classList.add('hidden')" class="px-4 bg-slate-100 text-slate-500 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const btnAbsen = document.getElementById('btn_absen');
        if (btnAbsen) {
            const video = document.getElementById('kamera');
            const statusLokasi = document.getElementById('status_lokasi');
            navigator.mediaDevices.getUserMedia({ video: true }).then(s => video.srcObject = s);
            navigator.geolocation.getCurrentPosition(p => {
                document.getElementById('lokasi').value = p.coords.latitude + "," + p.coords.longitude;
                statusLokasi.innerHTML = "📍 GPS Terkunci: " + p.coords.latitude.toFixed(4) + ", " + p.coords.longitude.toFixed(4);
                statusLokasi.className = "text-xs text-green-600 mb-4 font-bold";
            });
            btnAbsen.onclick = () => {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                document.getElementById('foto').value = canvas.toDataURL('image/jpeg');
                document.getElementById('form_absen').submit();
            };
        }
    </script>
</body>
</html>