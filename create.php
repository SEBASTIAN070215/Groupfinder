<?php
// Memulai session dan menghubungkan ke database buatan Derryl
require_once 'koneksi.php';

// Cek apakah mahasiswa sudah login (session dari halaman login Panji)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

// Jika tombol "Posting Lowongan" ditekan
if (isset($_POST['submit'])) {
    $user_id     = $_SESSION['user_id'];
    $nama_matkul = trim($_POST['nama_matkul']);
    $kuota       = trim($_POST['kuota_dibutuhkan']);
    $deskripsi   = trim($_POST['deskripsi']);
    $kriteria    = trim($_POST['kriteria']);

    // Validasi sederhana agar tidak kosong
    if (empty($nama_matkul) || empty($kuota) || empty($deskripsi) || empty($kriteria)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Amankan data dari SQL injection
        $user_id_esc     = mysqli_real_escape_string($conn, $user_id);
        $nama_matkul_esc = mysqli_real_escape_string($conn, $nama_matkul);
        $kuota_esc       = mysqli_real_escape_string($conn, $kuota);
        $deskripsi_esc   = mysqli_real_escape_string($conn, $deskripsi);
        $kriteria_esc    = mysqli_real_escape_string($conn, $kriteria);

        // Query simpan ke database MySQL
        $query = "INSERT INTO study_groups (user_id, nama_matkul, kuota_dibutuhkan, deskripsi, kriteria) VALUES ('$user_id_esc', '$nama_matkul_esc', '$kuota_esc', '$deskripsi_esc', '$kriteria_esc')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Lowongan berhasil diposting!'); window.location='index.php';</script>";
            exit();
        } else {
            $error = "Gagal memposting lowongan: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Lowongan Kelompok - GroupFinder</title>
    <!-- Tailwind CSS CDN (Tampilan modern tanpa install framework) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-xl w-full bg-white rounded-xl shadow-md p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Buat Lowongan Kelompok</h2>
            <a href="index.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
        </div>

        <?php if(!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Nama Mata Kuliah</label>
                <input type="text" name="nama_matkul" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Pemrograman Web" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Jumlah Orang yang Dicari (Kuota)</label>
                <input type="number" name="kuota_dibutuhkan" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Deskripsi Singkat Tugas</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Jelaskan sedikit tentang tugas kelompoknya..." required></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Syarat / Kriteria Anggota</label>
                <input type="text" name="kriteria" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Bisa HTML/CSS, aktif, dan rajin" required>
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 font-medium transition duration-200 shadow-sm">
                Posting Lowongan
            </button>
        </form>
    </div>

</body>
</html>
