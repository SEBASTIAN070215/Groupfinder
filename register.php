<?php
session_start();
include 'config/koneksi.php'; // Sesuaikan jalur folder koneksi jika berbeda

$pesan = "";
$error = "";

if (isset($_POST['register'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    // Cek apakah NIM sudah pernah didaftarkan sebelumnya
    $cek_nim = mysqli_query($conn, "SELECT * FROM users WHERE nim = '$nim'");
    if (mysqli_num_rows($cek_nim) > 0) {
        $error = "NIM tersebut sudah terdaftar! Silakan langsung login.";
    } else {
        // Masukkan data mahasiswa baru ke database
        $query = "INSERT INTO users (nim, nama) VALUES ('$nim', '$nama')";
        if (mysqli_query($conn, $query)) {
            $pesan = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan, coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - GroupFinder</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Daftar Akun GroupFinder</h2>
        
        <?php if (!empty($error)) : ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?= $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($pesan)) : ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm"><?= $pesan; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">NIM Mahasiswa</label>
                <input type="text" name="nim" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 672025164">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama lengkap kamu">
            </div>
            <button type="submit" name="register" class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 transition mb-3">Daftar</button>
            <p class="text-center text-sm text-gray-600">Sudah punya akun? <a href="login.php" class="text-blue-600 font-medium hover:underline">Login di sini</a></p>
        </form>
    </div>
</body>
</html>
