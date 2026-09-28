<?php

session_start();

require_once __DIR__ . "/../config/db.php";

$errors = [];

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    header("Location: index.php");
    exit;

}


// Ambil data berdasarkan ID

$stmt = $pdo->prepare(
    "SELECT * FROM sepatu WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$data = $stmt->fetch();


if (!$data) {

    header("Location: index.php");
    exit;

}


$nama = $data["nama"];
$kategori = $data["kategori"];
$ukuran = $data["ukuran"];
$harga = $data["harga"];
$stok = $data["stok"];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $ukuran = (int)($_POST["ukuran"] ?? 0);
    $harga = (float)($_POST["harga"] ?? 0);
    $stok = (int)($_POST["stok"] ?? 0);


    // Validasi nama

    if (strlen($nama) < 3) {

        $errors[] =
            "Nama sepatu minimal 3 karakter.";

    }


    // Validasi ukuran

    if ($ukuran < 30 || $ukuran > 50) {

        $errors[] =
            "Ukuran sepatu harus antara 30 sampai 50.";

    }


    // Validasi harga

    if ($harga <= 0) {

        $errors[] =
            "Harga harus lebih besar dari 0.";

    }


    // Validasi stok

    if ($stok < 0) {

        $errors[] =
            "Stok tidak boleh kurang dari 0.";

    }


    if ($kategori === "") {

        $kategori = "Umum";

    }


    if (empty($errors)) {

        try {

            $sql = "UPDATE sepatu
                    SET nama = :nama,
                        kategori = :kategori,
                        ukuran = :ukuran,
                        harga = :harga,
                        stok = :stok
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([

                "nama" => $nama,

                "kategori" => $kategori,

                "ukuran" => $ukuran,

                "harga" => $harga,

                "stok" => $stok,

                "id" => $id

            ]);


            // PRG

            header(
                "Location: index.php?status=updated"
            );

            exit;


        } catch (PDOException $e) {

            if ($e->getCode() === "23000") {

                $errors[] =
                    "Nama sepatu sudah digunakan.";

            } else {

                $errors[] =
                    "Gagal memperbarui data.";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Sepatu</title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>


<body>

<header class="header">

    <div class="container">

        <h1>✏️ Edit Sepatu</h1>

        <p>Perbarui data sepatu</p>

    </div>

</header>


<main class="container">

    <div class="form-container">

        <a href="index.php" class="back">
            ← Kembali
        </a>


        <h2>Edit Data Sepatu</h2>


        <?php if (!empty($errors)): ?>

            <div class="alert error">

                <ul>

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <form method="POST">


            <label>
                Nama Sepatu
            </label>

            <input
                type="text"
                name="nama"
                value="<?= htmlspecialchars($nama) ?>"
                required
            >


            <label>
                Kategori
            </label>

            <input
                type="text"
                name="kategori"
                value="<?= htmlspecialchars($kategori) ?>"
            >


            <label>
                Ukuran
            </label>

            <input
                type="number"
                name="ukuran"
                value="<?= htmlspecialchars($ukuran) ?>"
                min="30"
                max="50"
                required
            >


            <label>
                Harga
            </label>

            <input
                type="number"
                name="harga"
                value="<?= htmlspecialchars($harga) ?>"
                min="1"
                required
            >


            <label>
                Stok
            </label>

            <input
                type="number"
                name="stok"
                value="<?= htmlspecialchars($stok) ?>"
                min="0"
                required
            >


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn add"
                >
                    Update
                </button>

                <a
                    href="index.php"
                    class="btn secondary"
                >
                    Batal
                </a>

            </div>


        </form>

    </div>

</main>

</body>
</html>