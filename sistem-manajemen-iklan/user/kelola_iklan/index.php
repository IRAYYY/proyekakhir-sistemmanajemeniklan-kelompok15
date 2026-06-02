<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
}

$user_id = $_SESSION['id'];

$query = mysqli_query($conn,
    "SELECT iklan.*,
            jenis_iklan.nama_jenis
     FROM iklan
     JOIN jenis_iklan
     ON iklan.jenis_iklan_id = jenis_iklan.id
     WHERE iklan.user_id='$user_id'
     AND iklan.status != 'Selesai'
     ORDER BY iklan.id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Kelola Iklan</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- MAIN CONTENT -->
<div class="lg:ml-72 mt-24 p-4 sm:p-6">

    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>

                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Kelola Iklan
                </h2>

                <p class="text-gray-500 text-sm sm:text-base mt-1">
                    Kelola seluruh iklan Anda
                </p>

            </div>

            <a href="../pesan_iklan/index.php"
               class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-center font-semibold w-full sm:w-auto">

                + Pesan Iklan

            </a>

        </div>

        <!-- MOBILE CARD -->
        <div class="grid grid-cols-1 gap-5 lg:hidden">

            <?php if(mysqli_num_rows($query) > 0) : ?>

                <?php while($data = mysqli_fetch_assoc($query)) : ?>

                    <?php

                    $warna = '';

                    if ($data['status'] == 'Belum Dibayar') {

                        $warna = 'bg-red-100 text-red-600';
                    }

                    elseif ($data['status'] == 'Pending') {

                        $warna = 'bg-orange-100 text-orange-600';
                    }

                    elseif ($data['status'] == 'Proses') {

                        $warna = 'bg-yellow-100 text-yellow-600';
                    }

                    elseif ($data['status'] == 'Aktif') {

                        $warna = 'bg-green-100 text-green-600';
                    }

                    else {

                        $warna = 'bg-gray-100 text-gray-600';
                    }

                    ?>

                    <div class="border border-gray-200 rounded-2xl p-4">

                        <!-- MEDIA -->
                        <div class="mb-4">

                            <?php if($data['tipe_media'] == 'foto') : ?>

                                <img src="../../assets/uploads/iklan/<?= $data['media']; ?>"
                                     class="w-full h-52 object-cover rounded-xl">

                            <?php else : ?>

                                <video controls
                                       class="w-full rounded-xl">

                                    <source src="../../assets/uploads/iklan/<?= $data['media']; ?>">

                                </video>

                            <?php endif; ?>

                        </div>

                        <!-- CONTENT -->
                        <div class="space-y-3">

                            <div>

                                <h3 class="text-lg font-bold text-gray-800">

                                    <?= $data['judul_iklan']; ?>

                                </h3>

                                <p class="text-sm text-gray-500 mt-1">

                                    <?= substr($data['deskripsi'], 0, 100); ?>...

                                </p>

                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">

                                <div>

                                    <p class="text-gray-500">
                                        Jenis
                                    </p>

                                    <p class="font-semibold">
                                        <?= $data['nama_jenis']; ?>
                                    </p>

                                </div>

                                <div>

                                    <p class="text-gray-500">
                                        Harga
                                    </p>

                                    <p class="font-semibold text-blue-600">

                                        Rp <?= number_format($data['harga']); ?>

                                    </p>

                                </div>

                            </div>

                            <div>

                                <p class="text-gray-500 text-sm">
                                    Jadwal
                                </p>

                                <p class="font-medium text-sm">

                                    <?= $data['tanggal_mulai']; ?>

                                    s/d

                                    <?= $data['tanggal_selesai']; ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-gray-500 text-sm mb-1">
                                    Invoice
                                </p>

                                <span class="bg-gray-100 px-3 py-2 rounded-lg text-xs break-all inline-block">

                                    <?= $data['kode_pembayaran']; ?>

                                </span>

                            </div>

                            <div>

                                <span class="<?= $warna; ?> px-3 py-2 rounded-lg text-sm inline-block font-semibold">

                                    <?= $data['status']; ?>

                                </span>

                            </div>

                            <!-- ACTION -->
                            <div class="flex flex-col gap-2 pt-2">

                                <!-- BELUM DIBAYAR -->
                                <?php if($data['status'] == 'Belum Dibayar') : ?>

                                    <a href="../pembayaran/index.php?id=<?= $data['id']; ?>"
                                       class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-3 rounded-xl text-center font-semibold">

                                        Bayar

                                    </a>

                                    <a href="edit.php?id=<?= $data['id']; ?>"
                                       class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-4 py-3 rounded-xl text-center font-semibold">

                                        Edit

                                    </a>

                                    <a href="javascript:void(0)"
                                       onclick="openModal('hapus.php?id=<?= $data['id']; ?>')"
                                       class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-3 rounded-xl text-center font-semibold">

                                        Hapus

                                    </a>

                                <!-- PENDING -->
                                <?php elseif($data['status'] == 'Pending') : ?>

                                    <div class="bg-orange-100 text-orange-600 px-4 py-3 rounded-xl text-center font-semibold">

                                        Menunggu Verifikasi

                                    </div>

                                <!-- PROSES / AKTIF -->
                                <?php else : ?>

                                    <a href="invoice.php?id=<?= $data['id']; ?>"
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 transition text-white px-4 py-3 rounded-xl text-center font-semibold">

                                        Invoice PDF

                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="text-center py-16">

                    <div class="text-5xl mb-4">
                        📢
                    </div>

                    <h3 class="text-xl font-bold text-gray-700 mb-2">

                        Belum Ada Iklan

                    </h3>

                    <p class="text-gray-500 mb-6">

                        Anda belum memiliki iklan aktif

                    </p>

                    <a href="../pesan_iklan/index.php"
                       class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl inline-block">

                        Pesan Iklan Sekarang

                    </a>

                </div>

            <?php endif; ?>

        </div>

        <!-- DESKTOP TABLE -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full min-w-[1200px]">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Media
                        </th>

                        <th class="p-4 text-left">
                            Judul
                        </th>

                        <th class="p-4 text-left">
                            Jenis
                        </th>

                        <th class="p-4 text-left">
                            Harga
                        </th>

                        <th class="p-4 text-left">
                            Jadwal
                        </th>

                        <th class="p-4 text-left">
                            Invoice
                        </th>

                        <th class="p-4 text-left">
                            Status
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

                        <!-- MEDIA -->
                        <td class="p-4">

                            <?php if($data['tipe_media'] == 'foto') : ?>

                                <img src="../../assets/uploads/iklan/<?= $data['media']; ?>"
                                     class="w-24 h-24 object-cover rounded-xl">

                            <?php else : ?>

                                <video controls
                                       class="w-40 rounded-xl">

                                    <source src="../../assets/uploads/iklan/<?= $data['media']; ?>">

                                </video>

                            <?php endif; ?>

                        </td>

                        <!-- JUDUL -->
                        <td class="p-4">

                            <div class="font-semibold text-gray-800">

                                <?= $data['judul_iklan']; ?>

                            </div>

                            <div class="text-sm text-gray-500 mt-1">

                                <?= substr($data['deskripsi'], 0, 50); ?>...

                            </div>

                        </td>

                        <!-- JENIS -->
                        <td class="p-4">

                            <?= $data['nama_jenis']; ?>

                        </td>

                        <!-- HARGA -->
                        <td class="p-4 font-semibold text-blue-600">

                            Rp <?= number_format($data['harga']); ?>

                        </td>

                        <!-- JADWAL -->
                        <td class="p-4">

                            <div>
                                <?= $data['tanggal_mulai']; ?>
                            </div>

                            <div class="text-gray-500">
                                s/d <?= $data['tanggal_selesai']; ?>
                            </div>

                        </td>

                        <!-- INVOICE -->
                        <td class="p-4">

                            <span class="bg-gray-100 px-3 py-2 rounded-lg text-sm">

                                <?= $data['kode_pembayaran']; ?>

                            </span>

                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            <?php

                            $warna = '';

                            if ($data['status'] == 'Belum Dibayar') {

                                $warna = 'bg-red-100 text-red-600';
                            }

                            elseif ($data['status'] == 'Pending') {

                                $warna = 'bg-orange-100 text-orange-600';
                            }

                            elseif ($data['status'] == 'Proses') {

                                $warna = 'bg-yellow-100 text-yellow-600';
                            }

                            elseif ($data['status'] == 'Aktif') {

                                $warna = 'bg-green-100 text-green-600';
                            }

                            else {

                                $warna = 'bg-gray-100 text-gray-600';
                            }

                            ?>

                            <span class="<?= $warna; ?> px-3 py-2 rounded-lg text-sm font-semibold">

                                <?= $data['status']; ?>

                            </span>

                        </td>

                        <!-- AKSI -->
                        <td class="p-4">

                            <div class="flex flex-col gap-2 min-w-[180px]">

                                <!-- BELUM DIBAYAR -->
                                <?php if($data['status'] == 'Belum Dibayar') : ?>

                                    <a href="../pembayaran/index.php?id=<?= $data['id']; ?>"
                                       class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-lg text-center">

                                        Bayar

                                    </a>

                                    <a href="edit.php?id=<?= $data['id']; ?>"
                                       class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-4 py-2 rounded-lg text-center">

                                        Edit

                                    </a>

                                    <a href="javascript:void(0)"
                                       onclick="openModal('hapus.php?id=<?= $data['id']; ?>')"
                                       class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-xl text-center">

                                        Hapus

                                    </a>

                                <!-- PENDING -->
                                <?php elseif($data['status'] == 'Pending') : ?>

                                    <div class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg text-center">

                                        Menunggu Verifikasi

                                    </div>

                                <!-- PROSES / AKTIF -->
                                <?php else : ?>

                                    <a href="invoice.php?id=<?= $data['id']; ?>"
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 transition text-white px-4 py-2 rounded-lg text-center">

                                        Invoice PDF

                                    </a>

                                <?php endif; ?>

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