<?php

session_start();

require_once __DIR__ . "/../config/db.php";

if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

// ===============================
// FITUR PENCARIAN
// ===============================

$search = isset($_GET["search"])
    ? trim($_GET["search"])
    : "";

if ($search != "") {

    $keyword = "%" . $search . "%";

    $stmt = $pdo->prepare("
        SELECT *
        FROM sepatu
        WHERE nama LIKE ?
        OR kategori LIKE ?
        ORDER BY id DESC
    ");

    $stmt->execute([
        $keyword,
        $keyword
    ]);

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM sepatu
        ORDER BY id DESC
    ");

    $stmt->execute();
}

$dataSepatu = $stmt->fetchAll();

$status = $_GET["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Toko Sepatu</title>

    <link rel="stylesheet"
          href="assets/style.css">

</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="header">

        <div>

            <h1>👟 Toko Sepatu</h1>

            <p>
                Sistem Manajemen Data Sepatu
            </p>

        </div>

        <a href="create.php"
           class="btn btn-primary">

            + Tambah Sepatu

        </a>

    </div>


    <!-- PESAN -->

    <?php if ($status == "created"): ?>

        <div class="alert success">
            Data berhasil ditambahkan!
        </div>

    <?php elseif ($status == "updated"): ?>

        <div class="alert success">
            Data berhasil diperbarui!
        </div>

    <?php elseif ($status == "deleted"): ?>

        <div class="alert success">
            Data berhasil dihapus!
        </div>

    <?php endif; ?>


    <!-- SEARCH -->

    <form
        action="index.php"
        method="GET"
        class="search-box"
    >

        <input
            type="text"
            name="search"
            placeholder="🔍 Cari nama atau kategori sepatu..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            🔍 Cari
        </button>

        <?php if ($search != ""): ?>

            <a
                href="index.php"
                class="btn btn-secondary"
            >
                Reset
            </a>

        <?php endif; ?>

    </form>


    <!-- HASIL PENCARIAN -->

    <?php if ($search != ""): ?>

        <div class="info">

            Hasil pencarian untuk:

            <strong>
                "<?= htmlspecialchars($search) ?>"
            </strong>

            — <?= count($dataSepatu) ?> data ditemukan.

        </div>

    <?php else: ?>

        <div class="info">

            Menampilkan
            <strong><?= count($dataSepatu) ?></strong>
            data sepatu.

        </div>

    <?php endif; ?>


    <!-- DATA SEPATU -->

    <div class="product-grid">

        <?php if (count($dataSepatu) > 0): ?>

            <?php foreach ($dataSepatu as $sepatu): ?>

                <div class="product-card">

                    <div class="product-icon">
                        👟
                    </div>

                    <h2>
                        <?= htmlspecialchars($sepatu["nama"]) ?>
                    </h2>

                    <p class="category">

                        <?= htmlspecialchars(
                            $sepatu["kategori"]
                        ) ?>

                    </p>


                    <div class="product-info">

                        <div>

                            <span>Ukuran</span>

                            <strong>
                                <?= htmlspecialchars(
                                    $sepatu["ukuran"]
                                ) ?>
                            </strong>

                        </div>


                        <div>

                            <span>Stok</span>

                            <strong>
                                <?= htmlspecialchars(
                                    $sepatu["stok"]
                                ) ?>
                            </strong>

                        </div>

                    </div>


                    <div class="price">

                        Rp
                        <?= number_format(
                            $sepatu["harga"],
                            0,
                            ",",
                            "."
                        ) ?>

                    </div>


                    <!-- ACTION -->

                    <div class="actions">

                        <a
                            href="edit.php?id=<?= $sepatu["id"] ?>"
                            class="btn btn-warning"
                        >
                            Edit
                        </a>


                        <form
                            action="delete.php"
                            method="POST"
                            onsubmit="return confirm(
                                'Yakin ingin menghapus sepatu ini?'
                            );"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $sepatu["id"] ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf"
                                value="<?= $_SESSION["csrf"] ?>"
                            >

                            <button
                                type="submit"
                                class="btn btn-danger"
                            >
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php else: ?>

            <!-- JIKA TIDAK ADA HASIL -->

            <div class="empty">

                <h2>🔎 Sepatu tidak ditemukan</h2>

                <p>
                    Tidak ada sepatu yang sesuai dengan
                    pencarian
                    "<strong><?= htmlspecialchars($search) ?></strong>".
                </p>

                <br>

                <a
                    href="index.php"
                    class="btn btn-secondary"
                >
                    ← Tampilkan Semua
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>