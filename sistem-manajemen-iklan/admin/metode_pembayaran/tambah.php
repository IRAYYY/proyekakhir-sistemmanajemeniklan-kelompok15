<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

if (isset($_POST['submit'])) {

    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    $foto = NULL;

    // UPLOAD FOTO
    if ($_FILES['foto']['name'] != '') {

        $namaFile =
            time() . '_' . $_FILES['foto']['name'];

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "../../assets/uploads/metode_pembayaran/" . $namaFile
        );

        $foto = $namaFile;
    }

    mysqli_query($conn,
        "INSERT INTO metode_pembayaran (
            judul,
            foto,
            deskripsi
        ) VALUES (
            '$judul',
            " . ($foto ? "'$foto'" : "NULL") . ",
            '$deskripsi'
        )"
    );

    echo "<script>
            alert('Metode pembayaran berhasil ditambahkan');
            window.location='index.php';
          </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Tambah Metode Pembayaran</title>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- CONTENT -->
<div class="lg:ml-72 mt-24 p-4 lg:p-8">

    <div class="max-w-4xl mx-auto bg-white p-5 lg:p-8 rounded-xl shadow">

        <!-- TITLE -->
        <div class="mb-8">

            <h2 class="text-2xl lg:text-3xl font-bold">

                Tambah Metode Pembayaran

            </h2>

            <p class="text-gray-500 mt-2">

                Tambahkan metode pembayaran baru ke sistem

            </p>

        </div>

        <!-- FORM -->
        <form method="POST"
              enctype="multipart/form-data"
              class="space-y-6">

            <!-- JUDUL -->
            <div>

                <label class="block mb-2 font-semibold text-gray-700">

                    Judul Metode

                </label>

                <input type="text"
                       name="judul"
                       placeholder="Masukkan judul metode pembayaran"
                       class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-lg transition"
                       required>

            </div>

            <!-- FOTO -->
            <div>

                <label class="block mb-2 font-semibold text-gray-700">

                    Upload Foto / QR

                </label>

                <input type="file"
                       name="foto"
                       class="w-full border border-gray-300 p-3 rounded-lg bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition">

                <p class="text-sm text-gray-500 mt-2">

                    Format gambar: JPG, PNG, JPEG

                </p>

            </div>

            <!-- DESKRIPSI -->
            <div>

                <label class="block mb-2 font-semibold text-gray-700">

                    Deskripsi

                </label>

                <textarea name="deskripsi"
                          rows="5"
                          placeholder="Masukkan deskripsi metode pembayaran"
                          class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-lg transition resize-none"
                          required></textarea>

            </div>

            <!-- BUTTON -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">

                <button type="submit"
                        name="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-lg font-medium w-full sm:w-auto">

                    Simpan

                </button>

                <a href="index.php"
                   class="bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-6 py-3 rounded-lg text-center font-medium w-full sm:w-auto">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>