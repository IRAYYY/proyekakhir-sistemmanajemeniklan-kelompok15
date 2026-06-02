<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
}

if (isset($_POST['submit'])) {

    $nama_jenis = htmlspecialchars($_POST['nama_jenis']);

    $tipe_media = $_POST['tipe_media'];

    $harga_foto = $_POST['harga_foto'] != ''
        ? $_POST['harga_foto']
        : NULL;

    $harga_video = $_POST['harga_video'] != ''
        ? $_POST['harga_video']
        : NULL;

    $harga_per_hari = $_POST['harga_per_hari'];

    mysqli_query($conn,
        "INSERT INTO jenis_iklan (
            nama_jenis,
            tipe_media,
            harga_foto,
            harga_video_per5detik,
            harga_per_hari
        ) VALUES (
            '$nama_jenis',
            '$tipe_media',
            " . ($harga_foto !== NULL ? "'$harga_foto'" : "NULL") . ",
            " . ($harga_video !== NULL ? "'$harga_video'" : "NULL") . ",
            '$harga_per_hari'
        )"
    );

    echo "<script>
            alert('Jenis iklan berhasil ditambahkan');
            window.location='index.php';
          </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Tambah Jenis Iklan</title>

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

    <div class="bg-white p-5 lg:p-8 rounded-2xl shadow">

        <!-- HEADER -->
        <div class="mb-8">

            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">

                Tambah Jenis Iklan

            </h2>

            <p class="text-gray-500 mt-2 text-sm lg:text-base">

                Tambahkan jenis iklan baru ke dalam sistem

            </p>

        </div>

        <!-- FORM -->
        <form method="POST">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <!-- NAMA -->
                <div class="lg:col-span-2">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Nama Jenis Iklan

                    </label>

                    <input type="text"
                           name="nama_jenis"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           placeholder="Masukkan nama jenis iklan"
                           required>

                </div>

                <!-- TIPE MEDIA -->
                <div class="lg:col-span-2">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Tipe Media

                    </label>

                    <select name="tipe_media"
                            id="tipe_media"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                            required>

                        <option value="">
                            -- Pilih Tipe Media --
                        </option>

                        <option value="foto">
                            Foto
                        </option>

                        <option value="video">
                            Video
                        </option>

                        <option value="keduanya">
                            Keduanya
                        </option>

                    </select>

                </div>

                <!-- HARGA FOTO -->
                <div id="fotoField">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Foto

                    </label>

                    <input type="number"
                           name="harga_foto"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           placeholder="Masukkan harga foto">

                </div>

                <!-- HARGA VIDEO -->
                <div id="videoField">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Video / 5 Detik

                    </label>

                    <input type="number"
                           name="harga_video"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           placeholder="Masukkan harga video">

                </div>

                <!-- HARGA PER HARI -->
                <div class="lg:col-span-2">

                    <label class="block mb-2 font-semibold text-gray-700">

                        Harga Per Hari

                    </label>

                    <input type="number"
                           name="harga_per_hari"
                           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl transition"
                           placeholder="Masukkan harga per hari"
                           required>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8">

                <button type="submit"
                        name="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl font-semibold">

                    Simpan Data

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

<script>

const tipeMedia = document.getElementById('tipe_media');

const fotoField = document.getElementById('fotoField');

const videoField = document.getElementById('videoField');

// FUNCTION
function toggleField() {

    let value = tipeMedia.value;

    fotoField.style.display = 'block';

    videoField.style.display = 'block';

    // FOTO
    if (value == 'foto') {

        videoField.style.display = 'none';
    }

    // VIDEO
    else if (value == 'video') {

        fotoField.style.display = 'none';
    }
}

// EVENT
tipeMedia.addEventListener('change', toggleField);

// INITIAL
toggleField();

</script>

</body>
</html>