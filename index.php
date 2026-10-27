<?php
// koneksi.php menyediakan $conn dan memulai session
require_once 'config/koneksi.php';

session_start();

// Cek login, sama seperti create.php (session dari login.php)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fitur pencarian sederhana berdasarkan nama mata kuliah
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";

if ($cari !== "") {
    $cari_esc = mysqli_real_escape_string($koneksi, $cari);
    $query = "SELECT * FROM study_groups WHERE nama_matkul LIKE '%$cari_esc%' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM study_groups ORDER BY id DESC";
}

$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lowongan Kelompok - GroupFinder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">

    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Lowongan Kelompok</h1>
            <a href="create.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium shadow-sm">
                + Buat Lowongan
            </a>
        </div>

        <form action="" method="GET" class="mb-6 flex gap-2">
            <input type="text" name="cari" value="<?= htmlspecialchars($cari); ?>"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                placeholder="Cari mata kuliah...">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 text-sm">Cari</button>
        </form>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-lg font-bold text-gray-800">
                                <?= htmlspecialchars($row['nama_matkul']); ?>
                            </h2>
                            <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">
                                Butuh <?= (int)$row['jumlah_anggota']; ?> orang
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">
                            <?= nl2br(htmlspecialchars($row['deskripsi'])); ?>
                        </p>

                        <div class="text-sm mb-4">
                            <span class="font-medium text-gray-700">Kriteria:</span>
                            <span class="text-gray-600"><?= htmlspecialchars($row['syarat_kriteria']); ?></span>
                        </div>

                        <?php if ($row['user_id'] == $user_id): ?>
                            <!-- Tombol hanya muncul untuk pembuat lowongan (dikerjakan Mikhael: edit.php, delete.php) -->
                            <div class="mt-auto flex gap-2 pt-3 border-t border-gray-100">
                                <a href="edit.php?id=<?= (int)$row['id']; ?>"
                                   class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs hover:bg-yellow-600">Edit</a>
                                <a href="delete.php?id=<?= (int)$row['id']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus lowongan ini?');"
                                   class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs hover:bg-red-700">Hapus</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500 text-sm">
                Belum ada lowongan kelompok<?= $cari !== "" ? " untuk pencarian tersebut" : ""; ?>.
            </div>
        <?php endif; ?>
    </div>

</body>
</html>