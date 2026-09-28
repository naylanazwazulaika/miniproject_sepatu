<?php

session_start();

require_once __DIR__ . "/../config/db.php";


// DELETE harus menggunakan POST

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}


// Cek CSRF

$csrf = $_POST["csrf"] ?? "";

if (
    empty($_SESSION["csrf"]) ||
    !hash_equals($_SESSION["csrf"], $csrf)
) {

    die("CSRF token tidak valid.");

}


// Validasi ID

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    header("Location: index.php");
    exit;

}


// Hapus data

$stmt = $pdo->prepare(
    "DELETE FROM sepatu WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);


// PRG

header(
    "Location: index.php?status=deleted"
);

exit;