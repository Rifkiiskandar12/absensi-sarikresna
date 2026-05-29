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
        <div class="font-bold text-xl tracking-wide flex items-center gap-2"><span>Panel HRD - Sari Kresna</span></div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-sm">Halo, <?= esc($username); ?> (<?= esc($role); ?>)</span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-xs font-bold transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 mt-6">
        
        <!-- ========================================== -->
        <!-- WIDGET ABSENSI MANDIRI (SAMA 100% DGN KARYAWAN) -->
        <!-- ========================================== -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-600 mb-8 max-w-2xl">
            <?php if (!$absen_hari_ini): ?>
                <h2 class="font-bold text-lg mb-4">📸 Absen Masuk (Mandiri HRD)</h2>
                <div class="aspect-video bg-slate-200 rounded-xl overflow-hidden mb-4"><video id="kamera" autoplay class="w-full h-full object-cover"></video></div>
                <p id="status_lokasi" class="text-xs text-amber-600 mb-4 font-bold italic">📍 Mencari koordinat GPS...</p>
                <form id="form_absen" action="<?= base_url('absensi/proses_masuk') ?>" method="POST">
                    <input type="hidden" id="foto" name="foto_masuk">
                    <input type="hidden" id="lokasi" name="lokasi_masuk">
                    <button type="button" id="btn_absen" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition">Ambil Foto & Absen Masuk</button>
                </form>

            <?php elseif ($absen_hari_ini['jam_keluar'] == null): ?>
                <h2 class="font-bold text-lg mb-4">📸 Absen Pulang (Mandiri HRD)</h2>
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
                    <p class="text-slate-500 text-sm">Selamat bekerja dan memantau karyawan!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-semibold shadow-sm italic flex justify-between items-center">
                <span>✅ <?= session()->getFlashdata('pesan') ?></span>
                <button onclick="this.parentElement.style.display='none'" class="text-green-900 font-bold">X</button>
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
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-1.5 shadow-sm">🖨️ PDF</button>
                <button type="submit" formaction="<?= base_url('dashboard/export_excel') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-1.5 shadow-sm">📊 Excel</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-600 mb-6">
                    <h2 class="font-bold text-xl text-slate-800 mb-4">Tambah Karyawan Baru</h2>
                    <form action="<?= base_url('hrd/simpan_karyawan') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3"><input type="text" name="nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="NIK (Contoh: KRY-004)" required></div>
                        <div class="mb-3"><input type="text" name="nama" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Nama Lengkap" required></div>
                        
                        <div class="mb-3">
                            <select name="divisi" onchange="checkDivisiKustom(this, 'box_divisi_add', 'input_divisi_add')" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white font-semibold text-slate-700" required>
                                <option value="" disabled selected>-- Pilih Departemen --</option>
                                <?php foreach($daftar_divisi as $div): ?>
                                    <option value="<?= esc($div) ?>"><?= esc($div) ?></option>
                                <?php endforeach; ?>
                                <option value="NEW_DIVISION" class="font-bold text-blue-600 bg-blue-50">➕ Tambah Divisi Baru...</option>
                            </select>
                        </div>
                        <div class="mb-3 hidden" id="box_divisi_add">
                            <input type="text" name="divisi_baru" id="input_divisi_add" class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg text-sm" placeholder="Ketik Nama Divisi Baru...">
                        </div>

                        <div class="mb-3">
                            <select name="shift" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white font-semibold text-slate-700">
                                <option value="Pagi">☀️ Shift Pagi (08:00 - 17:00)</option>
                                <option value="Siang">🌆 Shift Siang (13:00 - 21:00)</option>
                            </select>
                        </div>
                        <div class="mb-3"><input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Username Login" required></div>
                        <div class="mb-5"><input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Password" required></div>
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
                            <?php foreach($statistik_karyawan as $k): 
                                $isPagi = ($k['jam_masuk_shift'] == '08:00:00');
                            ?>
                            <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0 flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                                        <span><?= esc($k['nama']) ?></span>
                                        <span class="text-xs text-slate-400 font-normal"><?= esc($k['divisi']) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                                        <span>KPI: <?= $k['kpi'] ?>% | Cuti: <span class="<?= $k['sisa_cuti'] <= 2 ? 'text-red-500' : 'text-blue-600' ?>"><?= $k['sisa_cuti'] ?></span></span>
                                    </div>
                                </div>
                                <button onclick="bukaModalEdit(<?= $k['id_karyawan'] ?>, '<?= esc($k['nik']) ?>', '<?= esc($k['nama']) ?>', '<?= esc($k['divisi']) ?>', '<?= $isPagi ? 'Pagi' : 'Siang' ?>', '<?= esc($k['username']) ?>')" class="ml-2 text-[10px] bg-slate-100 hover:bg-amber-100 text-amber-700 font-bold px-2 py-1.5 rounded border border-slate-200 transition">✏️ Edit</button>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-4"><h2 class="font-bold text-lg text-slate-700">Monitoring Absensi Hari Ini</h2></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                    <th class="p-3 border-b">Karyawan</th>
                                    <th class="p-3 border-b">Waktu Masuk</th>
                                    <th class="p-3 border-b">Waktu Pulang</th>
                                    <th class="p-3 border-b text-center">Status</th>
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
                                        <td class="p-3"><div class="text-blue-600 font-bold"><?= $row['jam_masuk']; ?></div></td>
                                        <td class="p-3"><div class="text-orange-600 font-bold"><?= $row['jam_keluar'] ?? '--:--:--'; ?></div></td>
                                        <td class="p-3 text-center text-xs text-slate-400">Terpantau</td>
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

                <div class="bg-gradient-to-br from-indigo-50 to-white p-6 rounded-xl shadow-sm border border-indigo-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-lg text-indigo-900">📢 Buat Pengumuman Global</h2>
                        <?php if(!empty($pengumuman_terbaru)): ?>
                            <span class="text-[10px] bg-indigo-100 text-indigo-600 font-bold px-2 py-1 rounded">Update Terakhir: <?= date('d M Y', strtotime($pengumuman_terbaru['tanggal_posting'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="<?= base_url('dashboard/simpan_pengumuman') ?>" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="text" name="judul" class="w-full px-4 py-2 border border-indigo-200 rounded-lg text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Judul Pengumuman (Misal: Libur Nasional Idul Fitri)" required>
                        <textarea name="isi_pengumuman" rows="3" class="w-full px-4 py-3 border border-indigo-200 rounded-lg text-sm text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Tuliskan isi pesan secara detail di sini..." required></textarea>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition w-full md:w-auto">🚀 Broadcast ke Karyawan</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL EDIT KARYAWAN -->
    <div id="modalEdit" class="fixed inset-0 bg-slate-900/80 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden relative">
            <div class="bg-amber-500 p-4 flex justify-between items-center">
                <h3 class="font-black text-slate-900">✏️ Edit Data Karyawan</h3>
                <button onclick="tutupModalEdit()" class="text-slate-900 hover:text-white font-bold text-lg leading-none">×</button>
            </div>
            <form action="<?= base_url('dashboard/update_karyawan') ?>" method="POST" class="p-6">
                <?= csrf_field() ?>
                <input type="hidden" name="id_karyawan" id="edit_id_karyawan">
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">NIK</label>
                    <input type="text" name="nik" id="edit_nik" class="w-full px-3 py-2 border rounded-lg text-sm bg-slate-50" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Divisi / Departemen</label>
                    <select name="divisi" id="edit_divisi" onchange="checkDivisiKustom(this, 'box_divisi_edit', 'input_divisi_edit')" class="w-full px-3 py-2 border rounded-lg text-sm bg-white font-semibold text-slate-700" required>
                        <?php foreach($daftar_divisi as $div): ?>
                            <option value="<?= esc($div) ?>"><?= esc($div) ?></option>
                        <?php endforeach; ?>
                        <option value="NEW_DIVISION" class="font-bold text-amber-600 bg-amber-50">➕ Tambah Divisi Baru...</option>
                    </select>
                </div>
                <div class="mb-3 hidden" id="box_divisi_edit">
                    <input type="text" name="divisi_baru" id="input_divisi_edit" class="w-full px-3 py-2 border-2 border-amber-300 rounded-lg text-sm" placeholder="Ketik nama divisi baru...">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jadwal Shift Operasional</label>
                    <select name="shift" id="edit_shift" class="w-full px-3 py-2 border rounded-lg text-sm bg-white font-semibold text-slate-700">
                        <option value="Pagi">☀️ Shift Pagi (08:00 - 17:00)</option>
                        <option value="Siang">🌆 Shift Siang (13:00 - 21:00)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Username Login</label>
                    <input type="text" name="username" id="edit_username" class="w-full px-3 py-2 border rounded-lg text-sm bg-slate-50" required>
                </div>
                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-red-500 mb-1 uppercase tracking-wide">Password Baru (Kosongkan jika tetap)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="••••••••">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-2.5 rounded-lg font-bold hover:bg-slate-900 transition shadow-lg">Simpan Perubahan</button>
                    <button type="button" onclick="tutupModalEdit()" class="px-4 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkDivisiKustom(selectElement, boxId, inputId) {
            const box = document.getElementById(boxId); const input = document.getElementById(inputId);
            if (selectElement.value === 'NEW_DIVISION') { box.classList.remove('hidden'); input.required = true; input.focus(); } 
            else { box.classList.add('hidden'); input.required = false; input.value = ''; }
        }
        function bukaModalEdit(id, nik, nama, divisi, shift, username) {
            document.getElementById('edit_id_karyawan').value = id; 
            document.getElementById('edit_nik').value = nik; 
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_divisi').value = divisi; 
            document.getElementById('edit_shift').value = shift; 
            document.getElementById('edit_username').value = username;
            
            document.getElementById('edit_divisi').dispatchEvent(new Event('change'));
            document.getElementById('modalEdit').classList.remove('hidden');
        }
        function tutupModalEdit() { document.getElementById('modalEdit').classList.add('hidden'); }
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
</body>
</html>