<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi PT Sari Kresna Kimia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-4 border-blue-600">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-slate-800">Sari Kresna Kimia</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Informasi Absensi Karyawan</p>
        </div>

        <form action="<?= base_url('auth/proses_login') ?>" method="POST">
            <div class="mb-5">
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">Username / NIK</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan username..." required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan password..." required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                Masuk ke Sistem
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-slate-500">
            <p>&copy; 2026 PT. Sari Kresna Kimia</p>
        </div>
    </div>
</body>
</html>