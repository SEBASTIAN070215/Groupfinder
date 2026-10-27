<?php
session_start();
include 'config/koneksi.php'; // Sesuaikan jalur folder koneksi jika berbeda

$error = "";

if (isset($_POST['login'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    
    // Cek apakah NIM terdaftar di database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE nim = '$nim'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Simpan data ke dalam Session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['nim'] = $row['nim'];
        
        // Arahkan ke halaman utama (index.php) setelah berhasil login
        header("Location: index.php");
        exit;
    } else {
        $error = "NIM tidak ditemukan! Silakan daftar terlebih dahulu.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - GroupFinder</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login GroupFinder</h2>
        
        <?php if (!empty($error)) : ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Masukkan NIM Mahasiswa</label>
                <input type="text" name="nim" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 672025164">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 transition mb-3">Masuk</button>
            <p class="text-center text-sm text-gray-600">Belum punya akun? <a href="register.php" class="text-blue-600 font-medium hover:underline">Daftar di sini</a></p>
        </form>
    </div>
</body>
</html>
