<?php
// Memulai session dan menyambungkan ke database
require_once 'koneksi.php';

// Cek autentikasi user
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";

// Ambil data lowongan yang sesuai dengan ID dan milik user yang sedang login
$query_select = "SELECT * FROM groups WHERE id = '$id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query_select);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($result);

// Proses perbaruan data saat tombol submit ditekan
if (isset($_POST['submit'])) {
    $nama_matkul = trim($_POST['nama_matkul']);
    $kuota       = trim($_POST['kuota_dibutuhkan']);
    $deskripsi   = trim($_POST['deskripsi']);
    $kriteria    = trim($_POST['kriteria']);

    if (empty($nama_matkul) || empty($kuota) || empty($deskripsi) || empty($kriteria)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $nama_matkul_esc = mysqli_real_escape_string($conn, $nama_matkul);
        $kuota_esc       = mysqli_real_escape_string($conn, $kuota);
        $deskripsi_esc   = mysqli_real_escape_string($conn, $deskripsi);
        $kriteria_esc    = mysqli_real_escape_string($conn, $kriteria);

        $query_update = "UPDATE groups SET 
                            nama_matkul = '$nama_matkul_esc',
                            kuota_dibutuhkan = '$kuota_esc',
                            deskripsi = '$deskripsi_esc',
                            kriteria = '$kriteria_esc'
                         WHERE id = '$id' AND user_id = '$user_id'";

        if (mysqli_query($conn, $query_update)) {
            echo "<script>alert('Lowongan berhasil diperbarui!'); window.location='index.php';</script>";
            exit();
        } else {
            $error = "Gagal memperbarui lowongan: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan Kelompok - GroupFinder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-xl w-full bg-white rounded-xl shadow-md p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Lowongan Kelompok</h2>
            <a href="index.php" class="text-sm text-blue-600 hover:underline">&larr; Batal</a>
        </div>

        <?php if(!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Nama Mata Kuliah</label>
                <input type="text" name="nama_matkul" value="<?= htmlspecialchars($data['nama_matkul']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Jumlah Orang yang Dicari (Kuota)</label>
                <input type="number" name="kuota_dibutuhkan" min="1" value="<?= htmlspecialchars($data['kuota_dibutuhkan']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Deskripsi Singkat Tugas</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Syarat / Kriteria Anggota</label>
                <input type="text" name="kriteria" value="<?= htmlspecialchars($data['kriteria']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 font-medium transition duration-200 shadow-sm">
                Simpan Perubahan
            </button>
        </form>
    </div>

</body>
</html>