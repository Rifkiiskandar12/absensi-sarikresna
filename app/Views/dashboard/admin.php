<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT & Admin Panel - PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans relative">

    <nav class="bg-slate-900 text-slate-300 p-4 shadow-lg flex justify-between items-center border-b border-slate-700">
        <div class="font-bold text-xl tracking-wide flex items-center gap-3">
            <span class="text-white">Sistem Absensi</span>
            <span class="text-[10px] bg-indigo-600 text-white px-2 py-0.5 rounded uppercase font-bold tracking-widest">IT Administrator</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-sm text-slate-400">Login sebagai: <span class="text-white"><?= esc($username); ?></span></span>
            <a href="<?= base_url('auth/logout') ?>" class="bg-slate-800 hover:bg-red-600 text-white px-4 py-2 rounded text-xs font-bold transition">Logout Sistem</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 mt-6">
        
        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-6 shadow-sm font-bold rounded-r-lg">
                ✅ <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>

        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Role & User Management</h1>
                <p class="text-slate-500 text-sm mt-1">Pusat kendali seluruh akun pengguna PT Sari Kresna Kimia.</p>
            </div>
            <button onclick="document.getElementById('modalTambahAkun').classList.remove('hidden')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-md flex items-center gap-2">
                + Tambah Akun Karyawan
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-indigo-50 border-b border-indigo-100 p-4 flex justify-between items-center">
                    <h2 class="font-bold text-indigo-800">Akun Manajemen & Sistem</h2>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold">
                                <th class="p-4 border-b">Nama / Pemilik</th>
                                <th class="p-4 border-b">Username</th>
                                <th class="p-4 border-b">Role</th>
                                <th class="p-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($daftar_manajemen as $m): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold text-slate-700"><?= esc($m['hrd']); ?></td>
                                <td class="p-4 text-slate-500 font-mono text-xs"><?= esc($m['username']); ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold <?= $m['role'] == 'Admin' ? 'bg-indigo-100 text-indigo-700' : ($m['role'] == 'HRD' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') ?>">
                                        <?= esc($m['role']); ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="<?= base_url('admin/reset_manajemen/' . $m['id_hrd']); ?>" onclick="return confirm('Reset password akun <?= $m['hrd'] ?> menjadi 123456?')" class="text-xs text-indigo-600 font-bold hover:underline">Reset Pass</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-100 p-4 flex justify-between items-center">
                    <h2 class="font-bold text-blue-800">Akun Karyawan Operasional</h2>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold">
                                <th class="p-4 border-b">NIK & Nama</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($daftar_karyawan as $k): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="p-4">
                                    <div class="font-bold text-slate-700"><?= esc($k['nama']); ?></div>
                                    <div class="text-[10px] text-slate-500 font-mono"><?= esc($k['username']); ?> | <?= esc($k['nik']); ?></div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold <?= $k['status_aktif'] ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                        <?= $k['status_aktif'] ? 'Aktif' : 'Suspended'; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center flex gap-2 justify-center mt-2">
                                    <a href="<?= base_url('admin/reset_karyawan/' . $k['id_karyawan']); ?>" onclick="return confirm('Reset password karyawan ini menjadi 123456?')" class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded text-[10px] font-bold hover:bg-slate-200 transition">Reset Pass</a>
                                    
                                    <?php if($k['status_aktif']): ?>
                                        <a href="<?= base_url('admin/toggle_status/' . $k['id_karyawan'] . '/0'); ?>" onclick="return confirm('Yakin ingin menangguhkan (suspend) karyawan ini?')" class="bg-red-50 text-red-600 px-3 py-1.5 rounded text-[10px] font-bold hover:bg-red-100 transition shadow-sm border border-red-100">Suspend</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/toggle_status/' . $k['id_karyawan'] . '/1'); ?>" onclick="return confirm('Aktifkan kembali karyawan ini?')" class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded text-[10px] font-bold hover:bg-emerald-100 transition shadow-sm border border-emerald-100">Aktifkan</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="modalTambahAkun" class="fixed inset-0 bg-slate-900/80 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden relative">
            <div class="flex justify-between items-center p-4 border-b border-slate-100 bg-indigo-50">
                <h3 class="font-bold text-lg text-indigo-800">Tambah Karyawan Baru</h3>
                <button onclick="document.getElementById('modalTambahAkun').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition focus:outline-none font-bold">X</button>
            </div>
            
            <form action="<?= base_url('admin/simpan_karyawan') ?>" method="POST" class="p-6">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nomor Induk Karyawan (NIK)</label>
                    <input type="text" name="nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="KRY-005" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama..." required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Divisi / Jabatan</label>
                    <select name="divisi" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="Karyawan">Karyawan</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Username Login</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Password Sementara</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">Simpan Data</button>
                    <button type="button" onclick="document.getElementById('modalTambahAkun').classList.add('hidden')" class="px-4 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>