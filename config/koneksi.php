<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "groupfinder";


try {
    $koneksi = new mysqli($host, $user, $pass, $db);
    $koneksi->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}