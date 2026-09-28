<?php

session_start();

require_once __DIR__ . "/../config/db.php";

// CSRF
if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Cek CSRF
    if (
        empty($_POST["csrf"]) ||
        !hash_equals($_SESSION["csrf"], $_POST["csrf"])
    ) {
        die("CSRF token tidak valid.");
    }

    $nama = trim($_POST["nama"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $ukuran = (int) ($_POST["ukuran"] ?? 0);
    $harga = (float) ($_POST["harga"] ?? 0);
    $stok = (int) ($_POST["stok"] ?? 0);

    // Validasi
    if (strlen($nama) < 3) {

        $error = "Nama sepatu minimal 3 karakter.";

    } elseif ($kategori == "") {

        $error = "Kategori sepatu wajib diisi.";

    } elseif ($ukuran <= 0) {

        $error = "Ukuran sepatu harus lebih dari 0.";

    } elseif ($harga <= 0) {

        $error = "Harga harus lebih dari 0.";

    } elseif ($stok < 0) {

        $error = "Stok tidak boleh kurang dari 0.";

    } else {

        // Cek nama sudah ada atau belum
        $cek = $pdo->prepare("
            SELECT COUNT(*)
            FROM sepatu
            WHERE nama = ?
        ");

        $cek->execute([$nama]);

        if ($cek->fetchColumn() > 0) {

            $error = "Nama sepatu sudah terdaftar.";

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO sepatu
                (nama, kategori, ukuran, harga, stok)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $nama,
                $kategori,
                $ukuran,
                $harga,
                $stok
            ]);

            header("Location: index.php?status=created");
            exit;
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

    <title>Tambah Sepatu</title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>

<body>

<div class="container">

    <div class="add-card">

        <!-- HEADER -->

        <div class="add-header">

            <div class="add-icon">
                👟
            </div>

            <div>

                <h1>Tambah Sepatu</h1>

                <p>
                    Tambahkan produk sepatu baru
                </p>

            </div>

        </div>


        <!-- ERROR -->

        <?php if ($error != ""): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <!-- FORM -->

        <form
            method="POST"
            action=""
            class="shoe-form"
        >

            <input
                type="hidden"
                name="csrf"
                value="<?= $_SESSION["csrf"] ?>"
            >


            <!-- NAMA -->

            <div class="form-group">

                <label for="nama">
                    Nama Sepatu
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Contoh: Nike Air Force 1"
                    value="<?= htmlspecialchars($_POST["nama"] ?? "") ?>"
                    required
                >

            </div>


            <!-- KATEGORI -->

            <div class="form-group">

                <label for="kategori">
                    Kategori
                </label>

                <select
                    id="kategori"
                    name="kategori"
                    required
                >

                    <option value="">
                        Pilih kategori
                    </option>

                    <option
                        value="Sneakers"
                        <?= (($_POST["kategori"] ?? "") == "Sneakers") ? "selected" : "" ?>
                    >
                        Sneakers
                    </option>

                    <option
                        value="Casual"
                        <?= (($_POST["kategori"] ?? "") == "Casual") ? "selected" : "" ?>
                    >
                        Casual
                    </option>

                    <option
                        value="Running"
                        <?= (($_POST["kategori"] ?? "") == "Running") ? "selected" : "" ?>
                    >
                        Running
                    </option>

                    <option
                        value="Sport"
                        <?= (($_POST["kategori"] ?? "") == "Sport") ? "selected" : "" ?>
                    >
                        Sport
                    </option>

                </select>

            </div>


            <!-- UKURAN + STOK -->

            <div class="form-row">

                <div class="form-group">

                    <label for="ukuran">
                        Ukuran
                    </label>

                    <input
                        type="number"
                        id="ukuran"
                        name="ukuran"
                        placeholder="42"
                        value="<?= htmlspecialchars($_POST["ukuran"] ?? "") ?>"
                        min="1"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="stok">
                        Stok
                    </label>

                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        placeholder="10"
                        value="<?= htmlspecialchars($_POST["stok"] ?? "") ?>"
                        min="0"
                        required
                    >

                </div>

            </div>


            <!-- HARGA -->

            <div class="form-group">

                <label for="harga">
                    Harga
                </label>

                <div class="price-input">

                    <span>Rp</span>

                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        placeholder="1500000"
                        value="<?= htmlspecialchars($_POST["harga"] ?? "") ?>"
                        min="1"
                        required
                    >

                </div>

            </div>


            <!-- BUTTON -->

            <div class="form-actions">

                <a
                    href="index.php"
                    class="btn-back"
                >
                    ← Kembali
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    + Tambah Sepatu
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>