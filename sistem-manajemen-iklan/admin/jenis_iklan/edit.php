<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
}

$id = $_GET['id'];

$query = mysqli_query($conn,
    "SELECT * FROM jenis_iklan
     WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {

    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {

    $nama_jenis = htmlspecialchars($_POST['nama_jenis']);

    $tipe_media = $_POST['tipe_media'];

    $harga_foto = $_POST['harga_foto'];

    $harga_video = $_POST['harga_video'];

    $harga_per_hari = $_POST['harga_per_hari'];

    mysqli_query($conn,
        "UPDATE jenis_iklan SET
            nama_jenis='$nama_jenis',
            tipe_media='$tipe_media',
            harga_foto='$harga_foto',
            harga_video_per5detik='$harga_video',
            harga_per_hari='$harga_per_hari'
        WHERE id='$id'"
    );

    echo "<script>
            alert('Jenis iklan berhasil diupdate');
            window.location='index.php';
          </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Edit Jenis Iklan</title>

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
<div class="lg:ml-72 pt-24 p-4 lg:p-8">

    <div class="bg-white p-5 lg:p-8 rounded-2xl shadow">

        <!-- HEADER -->
        <div class="mb-8">

            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">

                Edit Jenis Iklan

            </h2>

            <p class="text-gray-500 mt-2 text-sm lg:text-base">

                Perbarui data jenis iklan sesuai kebutuhan

            </p>

        </div>

        <!-- FORM -->
        <form method="POST">

            <!-- GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <!-- NAMA -->
                <div class="lg:col-span-2">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Nama Jenis Iklan

                    </label>

                    <input type="text"
                           name="nama_jenis"
                           value="<?= $data['nama_jenis']; ?>"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           required>

                </div>

                <!-- TIPE MEDIA -->
                <div>

                    <label class="block mb-2 font-semibold text-gray-700">

                        Tipe Media

                    </label>

                    <select name="tipe_media"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition">

                        <option value="foto"
                            <?= $data['tipe_media'] == 'foto' ? 'selected' : ''; ?>>

                            Foto

                        </option>

                        <option value="video"
                            <?= $data['tipe_media'] == 'video' ? 'selected' : ''; ?>>

                            Video

                        </option>

                        <option value="keduanya"
                            <?= $data['tipe_media'] == 'keduanya' ? 'selected' : ''; ?>>

                            Keduanya

                        </option>

                    </select>

                </div>

                <!-- HARGA FOTO -->
                <div>

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Foto

                    </label>

                    <input type="number"
                           name="harga_foto"
                           value="<?= $data['harga_foto']; ?>"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition">

                </div>

                <!-- HARGA VIDEO -->
                <div>

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Video / 5 Detik

                    </label>

                    <input type="number"
                           name="harga_video"
                           value="<?= $data['harga_video_per5detik']; ?>"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition">

                </div>

                <!-- HARGA PER HARI -->
                <div>

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Per Hari

                    </label>

                    <input type="number"
                           name="harga_per_hari"
                           value="<?= $data['harga_per_hari']; ?>"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           required>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8">

                <button type="submit"
                        name="update"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl font-semibold">

                    Update Data

                </button>

                <a href="index.php"
                   class="bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-6 py-3 rounded-xl font-semibold text-center">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>