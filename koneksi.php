<?php
/**
 * KONFIGURASI KONEKSI DATABASE MYSQL
 * SMP ISLAM AL-KHOLILI KOTA PROBOLINGGO
 * 
 * File ini digunakan untuk menghubungkan website ke database MySQL di phpMyAdmin (XAMPP).
 */

$db_host = "localhost";       // Host database (default XAMPP: localhost)
$db_user = "root";            // Username database (default XAMPP: root)
$db_pass = "";                // Password database (default XAMPP: kosong)
$db_name = "smp_alkholili";    // Nama database di phpMyAdmin

// Membuat koneksi MySQL menggunakan MySQLi
$koneksi = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek apakah database terhubung
if (!$koneksi) {
    // Catatan: Jika database MySQL belum dibuat di phpMyAdmin, 
    // website tetap berjalan normal menggunakan data di folder /data/
    $db_connected = false;
    $db_error = mysqli_connect_error();
} else {
    $db_connected = true;
    mysqli_set_charset($koneksi, "utf8mb4");
}
?>
