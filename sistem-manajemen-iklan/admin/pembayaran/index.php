<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

/*
|--------------------------------------------------------------------------
| FILTER & SEARCH
|--------------------------------------------------------------------------
*/

$where = [];

if (isset($_GET['status']) && $_GET['status'] != '') {

    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $where[] = "iklan.status='$status'";
}

if (isset($_GET['search']) && $_GET['search'] != '') {

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $where[] = "iklan.kode_pembayaran LIKE '%$search%'";
}

$whereSql = '';

if (!empty($where)) {

    $whereSql = "WHERE " . implode(" AND ", $where);
}

/*
|--------------------------------------------------------------------------
| QUERY DATA
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,
    "SELECT

        iklan.*,
        users.nama,
        jenis_iklan.nama_jenis,
        metode_pembayaran.judul as metode

    FROM iklan

    JOIN users
    ON iklan.user_id = users.id

    JOIN jenis_iklan
    ON iklan.jenis_iklan_id = jenis_iklan.id

    LEFT JOIN metode_pembayaran
    ON iklan.metode_pembayaran_id =
       metode_pembayaran.id

    $whereSql

    ORDER BY iklan.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Kelola Pembayaran</title>

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

    <div class="bg-white p-5 lg:p-8 rounded-xl shadow">

        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

            <h2 class="text-2xl lg:text-3xl font-bold">

                Kelola Pembayaran

            </h2>

            <!-- FILTER & SEARCH -->
            <form method="GET"
                  class="flex flex-col lg:flex-row gap-3 w-full lg:w-auto">

                <!-- SEARCH -->
                <input type="text"
                       name="search"
                       placeholder="Cari Invoice..."
                       value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>"
                       class="border p-3 rounded-lg w-full lg:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <!-- FILTER -->
                <select name="status"
                        class="border p-3 rounded-lg w-full lg:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="Belum Dibayar"
                        <?= isset($_GET['status']) &&
                            $_GET['status'] == 'Belum Dibayar'
                            ? 'selected'
                            : ''; ?>>

                        Belum Dibayar

                    </option>

                    <option value="Pending"
                        <?= isset($_GET['status']) &&
                            $_GET['status'] == 'Pending'
                            ? 'selected'
                            : ''; ?>>

                        Pending

                    </option>

                    <option value="Proses"
                        <?= isset($_GET['status']) &&
                            $_GET['status'] == 'Proses'
                            ? 'selected'
                            : ''; ?>>

                        Proses

                    </option>

                    <option value="Aktif"
                        <?= isset($_GET['status']) &&
                            $_GET['status'] == 'Aktif'
                            ? 'selected'
                            : ''; ?>>

                        Aktif

                    </option>

                    <option value="Selesai"
                        <?= isset($_GET['status']) &&
                            $_GET['status'] == 'Selesai'
                            ? 'selected'
                            : ''; ?>>

                        Selesai

                    </option>

                </select>

                <!-- BUTTON -->
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-lg">

                    Cari

                </button>

                <!-- RESET -->
                <a href="index.php"
                   class="bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-5 py-3 rounded-lg text-center">

                    Reset

                </a>

            </form>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto rounded-xl">

            <table class="w-full min-w-[900px]">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Invoice
                        </th>

                        <th class="p-4 text-left">
                            User
                        </th>

                        <th class="p-4 text-left">
                            Jenis Iklan
                        </th>

                        <th class="p-4 text-left">
                            Metode
                        </th>

                        <th class="p-4 text-left">
                            Total
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

                <?php if(mysqli_num_rows($query) > 0) : ?>

                    <?php while($data = mysqli_fetch_assoc($query)) : ?>

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- INVOICE -->
                            <td class="p-4 font-semibold">

                                <?= $data['kode_pembayaran']; ?>

                            </td>

                            <!-- USER -->
                            <td class="p-4">

                                <?= $data['nama']; ?>

                            </td>

                            <!-- JENIS -->
                            <td class="p-4">

                                <?= $data['nama_jenis']; ?>

                            </td>

                            <!-- METODE -->
                            <td class="p-4">

                                <?= $data['metode']
                                    ? $data['metode']
                                    : '-'; ?>

                            </td>

                            <!-- TOTAL -->
                            <td class="p-4 font-semibold text-blue-600">

                                Rp <?= number_format($data['harga']); ?>

                            </td>

                            <!-- STATUS -->
                            <td class="p-4">

                                <?php

                                $warna = '';

                                if ($data['status'] == 'Belum Dibayar') {

                                    $warna =
                                    'bg-red-100 text-red-600';
                                }

                                elseif ($data['status'] == 'Pending') {

                                    $warna =
                                    'bg-orange-100 text-orange-600';
                                }

                                elseif ($data['status'] == 'Proses') {

                                    $warna =
                                    'bg-yellow-100 text-yellow-600';
                                }

                                elseif ($data['status'] == 'Aktif') {

                                    $warna =
                                    'bg-green-100 text-green-600';
                                }

                                else {

                                    $warna =
                                    'bg-gray-100 text-gray-600';
                                }

                                ?>

                                <span class="<?= $warna; ?> px-3 py-1 rounded-lg text-sm font-medium">

                                    <?= $data['status']; ?>

                                </span>

                            </td>

                            <!-- AKSI -->
                            <td class="p-4">

                                <div class="flex gap-2">

                                    <!-- DETAIL -->
                                    <a href="detail.php?id=<?= $data['id']; ?>"
                                       class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-lg">

                                        Detail

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else : ?>

                    <tr>

                        <td colspan="7"
                            class="p-8 text-center text-gray-500">

                            Data pembayaran tidak ditemukan.

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