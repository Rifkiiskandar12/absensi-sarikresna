<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pimpinan - PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans relative">

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

        <!-- ========================================== -->
        <!-- WIDGET ABSENSI MANDIRI (SAMA 100% DGN KARYAWAN) -->
        <!-- ========================================== -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-600 mb-8 max-w-2xl">
            <?php if (!$absen_hari_ini): ?>
                <h2 class="font-bold text-lg mb-4">📸 Absen Masuk (Mandiri Pimpinan)</h2>
                <div class="aspect-video bg-slate-200 rounded-xl overflow-hidden mb-4"><video id="kamera" autoplay class="w-full h-full object-cover"></video></div>
                <p id="status_lokasi" class="text-xs text-amber-600 mb-4 font-bold italic">📍 Mencari koordinat GPS...</p>
                <form id="form_absen" action="<?= base_url('absensi/proses_masuk') ?>" method="POST">
                    <input type="hidden" id="foto" name="foto_masuk">
                    <input type="hidden" id="lokasi" name="lokasi_masuk">
                    <button type="button" id="btn_absen" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition">Ambil Foto & Absen Masuk</button>
                </form>

            <?php elseif ($absen_hari_ini['jam_keluar'] == null): ?>
                <h2 class="font-bold text-lg mb-4">📸 Absen Pulang (Mandiri Pimpinan)</h2>
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
                    <p class="text-slate-500 text-sm">Terima kasih atas dedikasi Anda hari ini!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-semibold shadow-sm italic flex justify-between items-center">
                <span>✅ <?= session()->getFlashdata('pesan') ?></span>
                <button onclick="this.parentElement.style.display='none'" class="text-green-900 hover:text-green-700 font-bold">X</button>
            </div>
        <?php endif; ?>
        
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

        <div class="bg-gradient-to-br from-indigo-50 to-white p-6 rounded-2xl shadow-sm border border-indigo-100 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg text-indigo-900">📢 Buat Pengumuman Global</h2>
                <?php if(!empty($pengumuman_terbaru)): ?>
                    <span class="text-[10px] bg-indigo-100 text-indigo-600 font-bold px-2 py-1 rounded">Update Terakhir: <?= date('d M Y', strtotime($pengumuman_terbaru['tanggal_posting'])) ?></span>
                <?php endif; ?>
            </div>
            <form action="<?= base_url('dashboard/simpan_pengumuman') ?>" method="POST" class="space-y-3">
                <?= csrf_field() ?>
                <input type="text" name="judul" class="w-full px-4 py-2 border border-indigo-200 rounded-lg text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Judul Pengumuman Instruksi Direksi" required>
                <textarea name="isi_pengumuman" rows="3" class="w-full px-4 py-3 border border-indigo-200 rounded-lg text-sm text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Tuliskan isi pesan atau instruksi secara detail di sini..." required></textarea>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition w-full md:w-auto">🚀 Broadcast ke Seluruh Karyawan</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h2 class="font-bold text-lg text-slate-700">Detail Kehadiran Harian</h2>
                    <form method="GET" action="<?= base_url('dashboard') ?>" class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                        <input type="date" name="tanggal" value="<?= $tanggal_filter ?>" onchange="this.form.submit()" class="bg-white text-sm border border-slate-200 rounded px-2 py-1 font-semibold text-slate-700 cursor-pointer">
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <th class="p-3 border-b">Nama Karyawan</th>
                                <th class="p-3 border-b">Masuk</th>
                                <th class="p-3 border-b">Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(empty($rekap_absensi)): ?>
                                <tr><td colspan="3" class="p-6 text-center text-slate-400 italic">Tidak ada data kehadiran.</td></tr>
                            <?php else: ?>
                                <?php foreach($rekap_absensi as $row): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3">
                                        <div class="font-bold text-slate-700"><?= esc($row['nama']); ?></div>
                                        <div class="text-[10px] text-slate-400"><?= esc($row['nik']); ?></div>
                                    </td>
                                    <td class="p-3 text-emerald-600 font-bold text-xs"><?= $row['jam_masuk']; ?></td>
                                    <td class="p-3 text-amber-600 font-bold text-xs"><?= $row['jam_keluar'] ?? '--:--'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <div>
                        <h2 class="font-bold text-lg text-slate-700">⚙️ Manajemen Shift</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ubah pengaturan jam kerja karyawan</p>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                    <table class="w-full text-left text-sm border-collapse relative">
                        <thead class="sticky top-0 bg-white shadow-sm">
                            <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <th class="p-3 border-b">Karyawan</th>
                                <th class="p-3 border-b">Jadwal Saat Ini</th>
                                <th class="p-3 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($daftar_karyawan as $k): 
                                $isPagi = ($k['jam_masuk_shift'] == '08:00:00');
                                $namaShift = $isPagi ? 'Shift Pagi' : 'Shift Siang';
                                $jamShift = $isPagi ? '(08:00 - 17:00)' : '(13:00 - 21:00)';
                                $warnaShift = $isPagi ? 'bg-sky-100 text-sky-700' : 'bg-orange-100 text-orange-700';
                            ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3">
                                    <div class="font-bold text-slate-700"><?= esc($k['nama']); ?></div>
                                </td>
                                <td class="p-3">
                                    <span class="<?= $warnaShift ?> px-2 py-1 rounded-md text-[10px] font-black tracking-wide"><?= $namaShift ?></span>
                                    <div class="text-[10px] text-slate-500 mt-1 font-mono"><?= $jamShift ?></div>
                                </td>
                                <td class="p-3 text-center">
                                    <button onclick="bukaModalShift(<?= $k['id_karyawan'] ?>, '<?= esc($k['nama']) ?>', '<?= $isPagi ? 'Pagi' : 'Siang' ?>')" class="bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-700 px-3 py-1.5 rounded-lg text-[11px] font-bold transition border border-slate-200">
                                        ✎ Ubah Shift
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="modalShift" class="fixed inset-0 bg-slate-900/80 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden relative">
            <div class="bg-amber-500 p-4 flex justify-between items-center">
                <h3 class="font-black text-slate-900">Ubah Jadwal Shift</h3>
                <button onclick="tutupModalShift()" class="text-slate-900 hover:text-white font-bold text-lg leading-none">×</button>
            </div>
            
            <form action="<?= base_url('dashboard/update_shift') ?>" method="POST" class="p-6">
                <?= csrf_field() ?>
                <input type="hidden" name="id_karyawan" id="shift_id_karyawan">
                
                <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Nama Karyawan</p>
                <h4 id="shift_nama_karyawan" class="text-lg font-black text-slate-800 mb-6 border-b pb-2"></h4>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Shift Baru:</label>
                    <select name="shift" id="shift_select" class="w-full px-3 py-3 border-2 border-slate-200 rounded-xl font-bold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <option value="Pagi">☀️ Shift Pagi (08:00 - 17:00)</option>
                        <option value="Siang">🌆 Shift Siang (13:00 - 21:00)</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-3 rounded-xl font-bold hover:bg-slate-900 transition shadow-lg">Simpan Perubahan</button>
                    <button type="button" onclick="tutupModalShift()" class="px-5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModalShift(id, nama, currentShift) {
            document.getElementById('shift_id_karyawan').value = id;
            document.getElementById('shift_nama_karyawan').innerText = nama;
            document.getElementById('shift_select').value = currentShift;
            document.getElementById('modalShift').classList.remove('hidden');
        }

        function tutupModalShift() {
            document.getElementById('modalShift').classList.add('hidden');
        }
    </script>
    
    <!-- ========================================== -->
    <!-- SCRIPT KAMERA & GPS (SAMA 100% DGN KARYAWAN) -->
    <!-- ========================================== -->
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
    
    <style>
        @media print {
            nav, button, form, .bg-gradient-to-br, #modalShift { display: none !important; }
            body { background-color: white !important; }
            .shadow-sm, .shadow-md { box-shadow: none !important; }
        }
    </style>
</body>
</html>