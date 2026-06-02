<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$query = mysqli_query($conn,
    "SELECT * FROM metode_pembayaran
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Metode Pembayaran</title>

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

                    Metode Pembayaran

                </h2>

                <p class="text-gray-500 mt-1">

                    Kelola metode pembayaran sistem

                </p>

            </div>

            <a href="tambah.php"
               class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-lg text-center w-full sm:w-auto">

                Tambah Metode

            </a>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px]">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Foto
                        </th>

                        <th class="p-4 text-left">
                            Judul
                        </th>

                        <th class="p-4 text-left">
                            Deskripsi
                        </th>

                        <th class="p-4 text-left">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($query) > 0) : ?>

                    <?php while($data = mysqli_fetch_assoc($query)) : ?>

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- FOTO -->
                            <td class="p-4">

                                <?php if($data['foto']) : ?>

                                    <img src="../../assets/uploads/metode_pembayaran/<?= $data['foto']; ?>"
                                         class="w-24 lg:w-28 h-16 lg:h-20 object-cover rounded-lg border">

                                <?php else : ?>

                                    <div class="w-24 lg:w-28 h-16 lg:h-20 rounded-lg border bg-gray-100 flex items-center justify-center text-xs text-gray-400 text-center p-2">

                                        Tidak ada foto

                                    </div>

                                <?php endif; ?>

                            </td>

                            <!-- JUDUL -->
                            <td class="p-4 font-semibold">

                                <?= $data['judul']; ?>

                            </td>

                            <!-- DESKRIPSI -->
                            <td class="p-4 text-gray-600 break-words">

                                <?= $data['deskripsi']; ?>

                            </td>

                            <!-- AKSI -->
                            <td class="p-4">

                                <div class="flex flex-col sm:flex-row gap-2">

                                    <!-- EDIT -->
                                    <a href="edit.php?id=<?= $data['id']; ?>"
                                       class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-4 py-2 rounded-lg text-center">

                                        Edit

                                    </a>

                                    <!-- HAPUS -->
                                    <a href="javascript:void(0)"
                                       onclick="openModal('hapus.php?id=<?= $data['id']; ?>')"
                                       class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-lg text-center">

                                        Hapus

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else : ?>

                    <tr>

                        <td colspan="4"
                            class="p-8 text-center text-gray-500">

                            Data metode pembayaran belum tersedia.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>