<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';
$status          = $_GET['status'] ?? '';
$jenis           = $_GET['jenis'] ?? '';
$search          = $_GET['search'] ?? '';

$where = "WHERE iklan.status != 'Belum Dibayar'";

if ($tanggal_mulai && $tanggal_selesai) {

    $where .= " AND (
        iklan.tanggal_mulai BETWEEN '$tanggal_mulai'
        AND '$tanggal_selesai'
    )";
}

if ($status != '') {

    $where .= " AND iklan.status='$status'";
}

if ($jenis != '') {

    $where .= " AND iklan.jenis_iklan_id='$jenis'";
}

if ($search != '') {

    $where .= " AND (
        users.nama LIKE '%$search%'
        OR iklan.judul_iklan LIKE '%$search%'
        OR iklan.kode_pembayaran LIKE '%$search%'
    )";
}

$query = mysqli_query($conn,"
    SELECT
        iklan.*,
        users.nama,
        jenis_iklan.nama_jenis

    FROM iklan

    JOIN users
    ON iklan.user_id = users.id

    JOIN jenis_iklan
    ON iklan.jenis_iklan_id = jenis_iklan.id

    $where

    ORDER BY iklan.id DESC
");

$total_order = 0;
$total_omset = 0;
$total_pending = 0;
$total_proses = 0;
$total_aktif = 0;
$total_selesai = 0;

$dataLaporan = [];

while($row = mysqli_fetch_assoc($query)) {

    $dataLaporan[] = $row;

    $total_order++;

    $total_omset += $row['harga'];

    if ($row['status'] == 'Pending') {
        $total_pending++;
    }

    if ($row['status'] == 'Proses') {
        $total_proses++;
    }

    if ($row['status'] == 'Aktif') {
        $total_aktif++;
    }

    if ($row['status'] == 'Selesai') {
        $total_selesai++;
    }
}

$jenisQuery = mysqli_query($conn,"
    SELECT * FROM jenis_iklan
    ORDER BY nama_jenis ASC
");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Laporan</title>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    <!-- TITLE -->
    <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-4 mb-6">

        <div>

            <h1 class="text-2xl lg:text-3xl font-bold">
                Laporan & Statistik
            </h1>

            <p class="text-gray-500">
                Sistem laporan profesional
            </p>

        </div>

        <!-- EXPORT BUTTON -->
        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">

            <a href="export_pdf.php?<?= $_SERVER['QUERY_STRING']; ?>"
               target="_blank"
               class="bg-red-600 hover:bg-red-700 transition text-white px-5 py-3 rounded-xl text-center">

                Export PDF

            </a>

            <a href="export_excel.php?<?= $_SERVER['QUERY_STRING']; ?>"
               class="bg-green-600 hover:bg-green-700 transition text-white px-5 py-3 rounded-xl text-center">

                Export Excel

            </a>

        </div>

    </div>

    <!-- CARD -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4 mb-6">

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Total Order
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold mt-2">

                <?= $total_order; ?>

            </h2>

        </div>

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Omset
            </p>

            <h2 class="text-xl lg:text-2xl font-bold mt-2 text-blue-600 break-words">

                Rp <?= number_format($total_omset); ?>

            </h2>

        </div>

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Pending
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold mt-2 text-orange-500">

                <?= $total_pending; ?>

            </h2>

        </div>

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Proses
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold mt-2 text-yellow-500">

                <?= $total_proses; ?>

            </h2>

        </div>

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Aktif
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold mt-2 text-green-500">

                <?= $total_aktif; ?>

            </h2>

        </div>

        <div class="bg-white p-4 lg:p-6 rounded-xl shadow">

            <p class="text-gray-500">
                Selesai
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold mt-2 text-gray-500">

                <?= $total_selesai; ?>

            </h2>

        </div>

    </div>

    <!-- FILTER -->
    <div class="bg-white p-4 lg:p-6 rounded-xl shadow mb-6">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

                <!-- TANGGAL MULAI -->
                <input type="date"
                       name="tanggal_mulai"
                       value="<?= $tanggal_mulai; ?>"
                       class="border rounded-xl p-3 w-full">

                <!-- TANGGAL SELESAI -->
                <input type="date"
                       name="tanggal_selesai"
                       value="<?= $tanggal_selesai; ?>"
                       class="border rounded-xl p-3 w-full">

                <!-- STATUS -->
                <select name="status"
                        class="border rounded-xl p-3 w-full">

                    <option value="">
                        Semua Status
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

                <!-- JENIS -->
                <select name="jenis"
                        class="border rounded-xl p-3 w-full">

                    <option value="">
                        Semua Jenis
                    </option>

                    <?php while($j = mysqli_fetch_assoc($jenisQuery)) : ?>

                        <option value="<?= $j['id']; ?>"
                            <?= $jenis == $j['id'] ? 'selected' : ''; ?>>

                            <?= $j['nama_jenis']; ?>

                        </option>

                    <?php endwhile; ?>

                </select>

                <!-- SEARCH -->
                <input type="text"
                       name="search"
                       placeholder="Cari invoice/user/judul..."
                       value="<?= $search; ?>"
                       class="border rounded-xl p-3 w-full">

            </div>

            <!-- BUTTON -->
            <button class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl mt-4 w-full md:w-auto">

                Filter Data

            </button>

        </form>

    </div>

    <!-- CHART -->
    <div class="gap-6 mb-6">

        <!-- STATUS CHART -->
        <div class="bg-white p-4 lg:p-6 rounded-xl shadow overflow-auto">

            <h2 class="text-xl font-bold mb-4">
                Statistik Status
            </h2>

            <canvas id="statusChart"></canvas>

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white p-4 lg:p-6 rounded-xl shadow overflow-x-auto">

        <table class="w-full min-w-[1000px]">

            <thead>

                <tr class="border-b bg-gray-50">

                    <th class="p-4 text-left">
                        Invoice
                    </th>

                    <th class="p-4 text-left">
                        User
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
                        Status
                    </th>

                    <th class="p-4 text-left">
                        Tanggal
                    </th>

                    <th class="p-4 text-left">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                <?php if(count($dataLaporan) > 0) : ?>

                    <?php foreach($dataLaporan as $d) : ?>

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold">

                            <?= $d['kode_pembayaran']; ?>

                        </td>

                        <td class="p-4">

                            <?= $d['nama']; ?>

                        </td>

                        <td class="p-4">

                            <?= $d['judul_iklan']; ?>

                        </td>

                        <td class="p-4">

                            <?= $d['nama_jenis']; ?>

                        </td>

                        <td class="p-4 font-bold text-blue-600">

                            Rp <?= number_format($d['harga']); ?>

                        </td>

                        <td class="p-4">

                            <?php
                            $warna = '';

                            if ($d['status'] == 'Pending') {
                                $warna = 'bg-orange-100 text-orange-600';
                            }

                            elseif ($d['status'] == 'Proses') {
                                $warna = 'bg-yellow-100 text-yellow-600';
                            }

                            elseif ($d['status'] == 'Aktif') {
                                $warna = 'bg-green-100 text-green-600';
                            }

                            else {
                                $warna = 'bg-gray-100 text-gray-600';
                            }
                            ?>

                            <span class="<?= $warna; ?> px-3 py-1 rounded-lg text-sm font-medium">

                                <?= $d['status']; ?>

                            </span>

                        </td>

                        <td class="p-4">

                            <?= $d['tanggal_mulai']; ?>

                        </td>

                        <td class="p-4">

                            <a href="detail.php?id=<?= $d['id']; ?>"
                               class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-lg">

                                Detail

                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else : ?>

                    <tr>

                        <td colspan="8"
                            class="p-8 text-center text-gray-500">

                            Data laporan tidak ditemukan.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script>

new Chart(document.getElementById('statusChart'), {

    type: 'bar',

    data: {

        labels: [
            'Pending',
            'Proses',
            'Aktif',
            'Selesai'
        ],

        datasets: [{
            label: 'Jumlah',
            data: [
                <?= $total_pending; ?>,
                <?= $total_proses; ?>,
                <?= $total_aktif; ?>,
                <?= $total_selesai; ?>
            ]
        }]
    }
});

// new Chart(document.getElementById('omsetChart'), {

//     type: 'doughnut',

//     data: {

//         labels: ['Total Omset'],

//         datasets: [{
//             data: [<?= $total_omset; ?>]
//         }]
//     }
// });

</script>

<?php include "../layouts/footer.php"; ?>

</body>
</html>