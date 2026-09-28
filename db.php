<?php

$host = "localhost";
$username = "root";
$password = "";

try {

    // Koneksi ke MySQL tanpa memilih database
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Membuat database otomatis
    $pdo->exec("
        CREATE DATABASE IF NOT EXISTS toko_sepatu
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci
    ");

    // Memilih database
    $pdo->exec("USE toko_sepatu");

    // Membuat tabel otomatis
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sepatu (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL UNIQUE,
            kategori VARCHAR(50) NOT NULL,
            ukuran INT NOT NULL,
            harga DECIMAL(12,2) NOT NULL,
            stok INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Cek jumlah data
    $cek = $pdo->query(
        "SELECT COUNT(*) FROM sepatu"
    );

    $jumlah = $cek->fetchColumn();

    // Data contoh hanya dimasukkan jika tabel masih kosong
    if ($jumlah == 0) {

        $stmt = $pdo->prepare("
            INSERT INTO sepatu
            (nama, kategori, ukuran, harga, stok)
            VALUES
            (?, ?, ?, ?, ?),
            (?, ?, ?, ?, ?),
            (?, ?, ?, ?, ?),
            (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            "Nike Air Force 1",
            "Sneakers",
            42,
            1500000,
            10,

            "Adidas Samba OG",
            "Sneakers",
            41,
            1800000,
            8,

            "Converse Chuck Taylor",
            "Casual",
            40,
            850000,
            15,

            "Vans Old Skool",
            "Casual",
            42,
            950000,
            12
        ]);
    }

    // Pengaturan PDO
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {

    die(
        "Koneksi database gagal: " .
        $e->getMessage()
    );

}