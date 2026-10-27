<?php
// Memulai session dan menyambungkan ke database
require_once 'koneksi.php';

// Cek autentikasi user
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Hapus data hanya jika ID sesuai dan merupakan milik user yang login
    $query = "DELETE FROM groups WHERE id = '$id' AND user_id = '$user_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Lowongan berhasil dihapus!'); window.location='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus lowongan!'); window.location='index.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>