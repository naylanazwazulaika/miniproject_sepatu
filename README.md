# TOKO SEPATU

Project Praktikum Pemrograman Web
Pertemuan 3 - Integrasi PHP, MySQL & UI Styling

## Fitur

- Create data sepatu
- Read data sepatu
- Update data sepatu
- Delete data sepatu
- Search sepatu
- Validasi server-side
- PDO
- Prepared Statement
- PRG (Post/Redirect/Get)
- XSS Protection
- CSRF Protection
- Responsive UI
- Flexbox

## Struktur

toko-sepatu/
├── config/
│   └── db.php
├── database/
│   └── store_db.sql
├── public/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── assets/
│       └── style.css
└── README.md

## Cara Menjalankan

1. Install XAMPP.

2. Jalankan:
   - Apache
   - MySQL

3. Letakkan folder toko-sepatu di:

C:\xampp\htdocs\

4. Buka phpMyAdmin.

5. Import file:

database/store_db.sql

6. Buka browser:

http://localhost/toko-sepatu/public/

## Database

Nama database:

toko_sepatu

Username:

root

Password:

kosong

## Validasi

Nama minimal 3 karakter.

Ukuran antara 30 sampai 50.

Harga harus lebih besar dari 0.

Stok tidak boleh kurang dari 0.

Nama sepatu tidak boleh sama.

## Keamanan

Semua query menggunakan PDO prepared statement.

Output menggunakan htmlspecialchars().

Delete menggunakan POST.

Delete menggunakan CSRF token.

## Teknologi

PHP
MySQL
PDO
HTML
CSS
XAMPP