<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
}

$query = mysqli_query($conn,
    "SELECT * FROM jenis_iklan
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Jenis Iklan</title>

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

    <div class="bg-white p-4 lg:p-8 rounded-xl shadow">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

            <div>

                <h2 class="text-2xl lg:text-3xl font-bold">

                    Jenis Iklan

                </h2>

                <p class="text-gray-500 mt-1 text-sm lg:text-base">

                    Kelola seluruh jenis iklan

                </p>

            </div>

            <a href="tambah.php"
               class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-lg text-center w-full sm:w-auto">

                Tambah Jenis Iklan

            </a>

        </div>

        <!-- MOBILE CARD -->
        <div class="block lg:hidden space-y-4">

            <?php while($data = mysqli_fetch_assoc($query)) : ?>

                <div class="border rounded-xl p-4 shadow-sm">

                    <div class="flex justify-between items-start gap-3 mb-4">

                        <div>

                            <h3 class="font-bold text-lg text-gray-800">

                                <?= $data['nama_jenis']; ?>

                            </h3>

                            <p class="text-sm text-gray-500 capitalize">

                                <?= $data['tipe_media']; ?>

                            </p>

                        </div>

                    </div>

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between gap-3">

                            <span class="text-gray-500">
                                Harga Foto
                            </span>

                            <span class="font-semibold text-right">

                                <?php if($data['harga_foto']) : ?>

                                    Rp <?= number_format($data['harga_foto']); ?>

                                <?php else : ?>

                                    -

                                <?php endif; ?>

                            </span>

                        </div>

                        <div class="flex justify-between gap-3">

                            <span class="text-gray-500">
                                Harga Video
                            </span>

                            <span class="font-semibold text-right">

                                <?php if($data['harga_video_per5detik']) : ?>

                                    Rp <?= number_format($data['harga_video_per5detik']); ?>

                                <?php else : ?>

                                    -

                                <?php endif; ?>

                            </span>

                        </div>

                        <div class="flex justify-between gap-3">

                            <span class="text-gray-500">
                                Harga Perhari
                            </span>

                            <span class="font-semibold text-right">

                                <?php if($data['harga_per_hari']) : ?>

                                    Rp <?= number_format($data['harga_per_hari']); ?>

                                <?php else : ?>

                                    -

                                <?php endif; ?>

                            </span>

                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-5">

                        <a href="edit.php?id=<?= $data['id']; ?>"
                           class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-4 py-2 rounded-lg text-center">

                            Edit

                        </a>

                        <a href="hapus.php?id=<?= $data['id']; ?>"
                           onclick="return confirm('Yakin hapus data?')"
                           class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-lg text-center">

                            Hapus

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

        <!-- DESKTOP TABLE -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Nama Jenis
                        </th>

                        <th class="p-4 text-left">
                            Tipe Media
                        </th>

                        <th class="p-4 text-left">
                            Harga Foto
                        </th>

                        <th class="p-4 text-left">
                            Harga Video / 5 Detik
                        </th>

                        <th class="p-4 text-left">
                            Harga Perhari
                        </th>

                        <th class="p-4 text-left">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php
                mysqli_data_seek($query, 0);

                while($data = mysqli_fetch_assoc($query)) :
                ?>

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold">

                            <?= $data['nama_jenis']; ?>

                        </td>

                        <td class="p-4 capitalize">

                            <?= $data['tipe_media']; ?>

                        </td>

                        <td class="p-4">

                            <?php if($data['harga_foto']) : ?>

                                Rp <?= number_format($data['harga_foto']); ?>

                            <?php else : ?>

                                -

                            <?php endif; ?>

                        </td>

                        <td class="p-4">

                            <?php if($data['harga_video_per5detik']) : ?>

                                Rp <?= number_format($data['harga_video_per5detik']); ?>

                            <?php else : ?>

                                -

                            <?php endif; ?>

                        </td>

                        <td class="p-4">

                            <?php if($data['harga_per_hari']) : ?>

                                Rp <?= number_format($data['harga_per_hari']); ?>

                            <?php else : ?>

                                -

                            <?php endif; ?>

                        </td>

                        <td class="p-4">

                            <div class="flex gap-2">

                                <a href="edit.php?id=<?= $data['id']; ?>"
                                   class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-4 py-2 rounded-lg">

                                    Edit

                                </a>

                                <a href="hapus.php?id=<?= $data['id']; ?>"
                                   onclick="return confirm('Yakin hapus data?')"
                                   class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-lg">

                                    Hapus

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>