<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

// =========================
// FILTER & SEARCH
// =========================
$where = "WHERE iklan.user_id='$user_id'";

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

if ($search != '') {

    $search = mysqli_real_escape_string($conn, $search);

    $where .= " AND (
        iklan.judul_iklan LIKE '%$search%'
        OR iklan.kode_pembayaran LIKE '%$search%'
        OR iklan.status LIKE '%$search%'
        OR iklan.tipe_media LIKE '%$search%'
    )";
}

if ($status != '') {

    $status = mysqli_real_escape_string($conn, $status);

    $where .= " AND iklan.status='$status'";
}

// =========================
// QUERY
// =========================
$query = mysqli_query($conn,
    "SELECT
        iklan.*,
        jenis_iklan.nama_jenis

     FROM iklan

     LEFT JOIN jenis_iklan
     ON iklan.jenis_iklan_id = jenis_iklan.id

     $where

     ORDER BY iklan.id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Riwayat Iklan</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- MAIN CONTENT -->
<div class="lg:ml-72 mt-24 p-4 sm:p-6">

    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 lg:p-8">

        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">

            <div>

                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Riwayat Iklan
                </h2>

                <p class="text-gray-500 mt-1 text-sm sm:text-base">
                    Semua riwayat pesanan iklan Anda
                </p>

            </div>

            <!-- FILTER -->
            <form method="GET"
                  class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <!-- SEARCH -->
                <div class="relative w-full sm:w-72">

                    <input type="text"
                           name="search"
                           placeholder="Cari judul / invoice / status..."
                           value="<?= htmlspecialchars($search); ?>"
                           class="w-full border border-gray-300 rounded-xl pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <!-- STATUS -->
                <select name="status"
                        class="border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="Belum Dibayar"
                        <?= $status == 'Belum Dibayar' ? 'selected' : ''; ?>>

                        Belum Dibayar

                    </option>

                    <option value="Pending"
                        <?= $status == 'Pending' ? 'selected' : ''; ?>>

                        Pending

                    </option>

                    <option value="Proses"
                        <?= $status == 'Proses' ? 'selected' : ''; ?>>

                        Proses

                    </option>

                    <option value="Aktif"
                        <?= $status == 'Aktif' ? 'selected' : ''; ?>>

                        Aktif

                    </option>

                    <option value="Selesai"
                        <?= $status == 'Selesai' ? 'selected' : ''; ?>>

                        Selesai

                    </option>

                </select>

                <!-- BUTTON -->
                <div class="flex gap-2">

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition w-full sm:w-auto">

                        Cari

                    </button>

                    <a href="index.php"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-xl transition text-center">

                        Reset

                    </a>

                </div>

            </form>

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

                        $warna = 'bg-yellow-100 text-yellow-700';
                    }

                    elseif ($data['status'] == 'Aktif') {

                        $warna = 'bg-green-100 text-green-600';
                    }

                    else {

                        $warna = 'bg-gray-100 text-gray-600';
                    }

                    ?>

                    <div class="border rounded-2xl p-4 shadow-sm">

                        <!-- MEDIA -->
                        <div class="mb-4">

                            <?php if ($data['tipe_media'] == 'foto') : ?>

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

                                <h3 class="font-bold text-lg text-gray-800">

                                    <?= $data['judul_iklan']; ?>

                                </h3>

                                <p class="text-sm text-gray-500 mt-1">

                                    <?= substr($data['deskripsi'], 0, 80); ?>...

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

                                <div>

                                    <p class="text-gray-500">
                                        Durasi
                                    </p>

                                    <p class="font-semibold">

                                        <?= $data['tipe_media'] == 'video'
                                            ? $data['durasi'] . ' detik'
                                            : '-'; ?>

                                    </p>

                                </div>

                                <div>

                                    <p class="text-gray-500">
                                        Status
                                    </p>

                                    <span class="<?= $warna; ?> px-3 py-1 rounded-lg text-xs font-semibold inline-block mt-1">

                                        <?= $data['status']; ?>

                                    </span>

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

                                <span class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm break-all inline-block">

                                    <?= $data['kode_pembayaran']; ?>

                                </span>

                            </div>

                            <!-- ACTION -->
                            <div class="pt-2">

                                <?php if($data['status'] == 'Pending') : ?>

                                    <div class="bg-orange-100 text-orange-600 px-4 py-3 rounded-xl text-center text-sm font-semibold">

                                        Menunggu Verifikasi

                                    </div>

                                <?php elseif(
                                    $data['status'] == 'Proses' ||
                                    $data['status'] == 'Aktif' ||
                                    $data['status'] == 'Selesai'
                                ) : ?>

                                    <a href="../kelola_iklan/invoice.php?id=<?= $data['id']; ?>"
                                       target="_blank"
                                       class="block bg-green-600 hover:bg-green-700 transition text-white text-center px-4 py-3 rounded-xl">

                                        Invoice PDF

                                    </a>

                                <?php else : ?>

                                    <div class="text-center text-gray-400 text-sm">

                                        Belum tersedia invoice

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="text-center py-16">

                    <h3 class="text-xl font-bold text-gray-700 mb-2">
                        Data tidak ditemukan
                    </h3>

                    <p class="text-gray-500">
                        Coba gunakan kata kunci lain
                    </p>

                </div>

            <?php endif; ?>

        </div>

        <!-- DESKTOP TABLE -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full border-collapse min-w-[1200px]">

                <thead>

                    <tr class="bg-gray-100 text-gray-700">

                        <th class="p-4 text-left rounded-l-xl">
                            Media
                        </th>

                        <th class="p-4 text-left">
                            Judul
                        </th>

                        <th class="p-4 text-left">
                            Jenis
                        </th>

                        <th class="p-4 text-left">
                            Durasi
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

                        <th class="p-4 text-left rounded-r-xl">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php
                mysqli_data_seek($query, 0);

                if(mysqli_num_rows($query) > 0) :
                ?>

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

                            $warna = 'bg-yellow-100 text-yellow-700';
                        }

                        elseif ($data['status'] == 'Aktif') {

                            $warna = 'bg-green-100 text-green-600';
                        }

                        else {

                            $warna = 'bg-gray-100 text-gray-600';
                        }

                        ?>

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- MEDIA -->
                            <td class="p-4">

                                <?php if ($data['tipe_media'] == 'foto') : ?>

                                    <img src="../../assets/uploads/iklan/<?= $data['media']; ?>"
                                         class="w-24 h-24 object-cover rounded-xl">

                                <?php else : ?>

                                    <video controls
                                           class="w-36 rounded-xl">

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

                                    <?= substr($data['deskripsi'],0,50); ?>...

                                </div>

                            </td>

                            <!-- JENIS -->
                            <td class="p-4">

                                <?= $data['nama_jenis']; ?>

                            </td>

                            <!-- DURASI -->
                            <td class="p-4">

                                <?= $data['tipe_media'] == 'video'
                                    ? $data['durasi'] . ' detik'
                                    : '-'; ?>

                            </td>

                            <!-- HARGA -->
                            <td class="p-4 font-semibold text-blue-600">

                                Rp <?= number_format($data['harga']); ?>

                            </td>

                            <!-- JADWAL -->
                            <td class="p-4 text-sm">

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

                                <span class="<?= $warna; ?> px-3 py-2 rounded-lg text-sm font-semibold">

                                    <?= $data['status']; ?>

                                </span>

                            </td>

                            <!-- AKSI -->
                            <td class="p-4">

                                <?php if($data['status'] == 'Pending') : ?>

                                    <div class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg text-center text-sm">

                                        Menunggu Verifikasi

                                    </div>

                                <?php elseif(
                                    $data['status'] == 'Proses' ||
                                    $data['status'] == 'Aktif' ||
                                    $data['status'] == 'Selesai'
                                ) : ?>

                                    <a href="../kelola_iklan/invoice.php?id=<?= $data['id']; ?>"
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 transition text-white px-4 py-2 rounded-lg text-center inline-block">

                                        Invoice PDF

                                    </a>

                                <?php else : ?>

                                    <p class="text-gray-400 text-center">
                                        -
                                    </p>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else : ?>

                    <tr>

                        <td colspan="9"
                            class="text-center py-16 text-gray-500">

                            Tidak ada data riwayat iklan

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