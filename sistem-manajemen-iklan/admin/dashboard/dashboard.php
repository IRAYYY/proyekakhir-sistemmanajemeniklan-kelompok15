<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

// =========================
// TOTAL ORDER
// =========================
$totalOrder = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan"
));

// =========================
// TOTAL OMSET
// =========================
$totalOmset = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(harga) as total
     FROM iklan
     WHERE status IN ('Proses','Aktif','Selesai')"
));

// =========================
// STATUS COUNTER
// =========================
$pending = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE status='Pending'"
));

$proses = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE status='Proses'"
));

$aktif = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE status='Aktif'"
));

$selesai = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE status='Selesai'"
));

// =========================
// IKLAN TERBARU
// =========================
$iklanTerbaru = mysqli_query($conn,
    "SELECT
        iklan.*,
        users.nama,
        jenis_iklan.nama_jenis

     FROM iklan

     JOIN users
     ON iklan.user_id = users.id

     JOIN jenis_iklan
     ON iklan.jenis_iklan_id = jenis_iklan.id

     ORDER BY iklan.id DESC
     LIMIT 5"
);

// =========================
// SLOT HARI INI
// =========================
$slotHariIni = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE CURDATE()
     BETWEEN tanggal_mulai
     AND tanggal_selesai"
));

// =========================
// DATA CHART BULANAN
// =========================
$dataChart = mysqli_query($conn,
    "SELECT

        MONTH(created_at) as bulan,
        COUNT(*) as total

     FROM iklan

     GROUP BY MONTH(created_at)

     ORDER BY MONTH(created_at)"
);

$bulan = [];
$totalIklan = [];

while ($row = mysqli_fetch_assoc($dataChart)) {

    $bulan[] = $row['bulan'];
    $totalIklan[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- MAIN CONTENT -->
<div class="lg:ml-72 mt-24 p-4 sm:p-6 lg:p-8">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">

            Dashboard Admin

        </h1>

        <p class="text-gray-500 mt-2 text-sm sm:text-base">

            Monitoring sistem manajemen iklan

        </p>

    </div>

    <!-- CARD STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- TOTAL ORDER -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow">

            <p class="text-gray-500 mb-2 text-sm sm:text-base">
                Total Order
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-blue-600">

                <?= $totalOrder['total']; ?>

            </h2>

        </div>

        <!-- OMSET -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow">

            <p class="text-gray-500 mb-2 text-sm sm:text-base">
                Total Omset
            </p>

            <h2 class="text-2xl sm:text-3xl font-bold text-green-600 break-words">

                Rp <?= number_format($totalOmset['total'] ?? 0); ?>

            </h2>

        </div>

        <!-- SLOT -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow">

            <p class="text-gray-500 mb-2 text-sm sm:text-base">
                Slot Hari Ini
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-orange-500">

                <?= $slotHariIni['total']; ?>/10

            </h2>

        </div>

        <!-- AKTIF -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow">

            <p class="text-gray-500 mb-2 text-sm sm:text-base">
                Iklan Aktif
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-purple-600">

                <?= $aktif['total']; ?>

            </h2>

        </div>

    </div>

    <!-- STATUS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-yellow-100 p-5 sm:p-6 rounded-2xl">

            <p class="text-yellow-700 font-semibold text-sm sm:text-base">
                Pending
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-yellow-800 mt-2">

                <?= $pending['total']; ?>

            </h2>

        </div>

        <div class="bg-blue-100 p-5 sm:p-6 rounded-2xl">

            <p class="text-blue-700 font-semibold text-sm sm:text-base">
                Proses
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 mt-2">

                <?= $proses['total']; ?>

            </h2>

        </div>

        <div class="bg-green-100 p-5 sm:p-6 rounded-2xl">

            <p class="text-green-700 font-semibold text-sm sm:text-base">
                Aktif
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-green-800 mt-2">

                <?= $aktif['total']; ?>

            </h2>

        </div>

        <div class="bg-gray-200 p-5 sm:p-6 rounded-2xl">

            <p class="text-gray-700 font-semibold text-sm sm:text-base">
                Selesai
            </p>

            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mt-2">

                <?= $selesai['total']; ?>

            </h2>

        </div>

    </div>

    <!-- CHART -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl shadow mb-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

            <div>

                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">

                    Statistik Order Bulanan

                </h2>

                <p class="text-gray-500 text-sm mt-1">

                    Grafik jumlah order iklan setiap bulan

                </p>

            </div>

        </div>

        <div class="w-full overflow-x-auto">

            <div class="min-w-[300px] h-[300px] sm:h-[400px]">

                <canvas id="chartIklan"></canvas>

            </div>

        </div>

    </div>

    <!-- IKLAN TERBARU -->
    <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl shadow">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

            <div>

                <h2 class="text-xl sm:text-2xl font-bold">

                    Iklan Terbaru

                </h2>

                <p class="text-gray-500 text-sm mt-1">

                    Data iklan terbaru pada sistem

                </p>

            </div>

            <a href="../pembayaran/index.php"
               class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-center text-sm sm:text-base">

                Lihat Semua

            </a>

        </div>

        <!-- TABLE DESKTOP -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full min-w-[800px]">

                <thead>

                    <tr class="border-b bg-gray-50">

                        <th class="text-left py-4 px-4 font-semibold text-gray-700">
                            User
                        </th>

                        <th class="text-left py-4 px-4 font-semibold text-gray-700">
                            Judul
                        </th>

                        <th class="text-left py-4 px-4 font-semibold text-gray-700">
                            Jenis
                        </th>

                        <th class="text-left py-4 px-4 font-semibold text-gray-700">
                            Harga
                        </th>

                        <th class="text-left py-4 px-4 font-semibold text-gray-700">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php mysqli_data_seek($iklanTerbaru, 0); ?>

                    <?php while($iklan = mysqli_fetch_assoc($iklanTerbaru)) : ?>

                    <?php
                    $warna = '';

                    if ($iklan['status'] == 'Pending') {
                        $warna = 'bg-yellow-100 text-yellow-700';
                    }
                    elseif ($iklan['status'] == 'Proses') {
                        $warna = 'bg-blue-100 text-blue-700';
                    }
                    elseif ($iklan['status'] == 'Aktif') {
                        $warna = 'bg-green-100 text-green-700';
                    }
                    elseif ($iklan['status'] == 'Selesai') {
                        $warna = 'bg-gray-200 text-gray-700';
                    }
                    else {
                        $warna = 'bg-red-100 text-red-700';
                    }
                    ?>

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="py-4 px-4">

                            <?= $iklan['nama']; ?>

                        </td>

                        <td class="py-4 px-4">

                            <?= $iklan['judul_iklan']; ?>

                        </td>

                        <td class="py-4 px-4">

                            <?= $iklan['nama_jenis']; ?>

                        </td>

                        <td class="py-4 px-4 font-semibold text-blue-600">

                            Rp <?= number_format($iklan['harga']); ?>

                        </td>

                        <td class="py-4 px-4">

                            <span class="<?= $warna; ?> px-3 py-1 rounded-lg text-sm font-medium">

                                <?= $iklan['status']; ?>

                            </span>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

        <!-- MOBILE CARD -->
        <div class="lg:hidden space-y-4">

            <?php mysqli_data_seek($iklanTerbaru, 0); ?>

            <?php while($iklan = mysqli_fetch_assoc($iklanTerbaru)) : ?>

            <?php
            $warna = '';

            if ($iklan['status'] == 'Pending') {
                $warna = 'bg-yellow-100 text-yellow-700';
            }
            elseif ($iklan['status'] == 'Proses') {
                $warna = 'bg-blue-100 text-blue-700';
            }
            elseif ($iklan['status'] == 'Aktif') {
                $warna = 'bg-green-100 text-green-700';
            }
            elseif ($iklan['status'] == 'Selesai') {
                $warna = 'bg-gray-200 text-gray-700';
            }
            else {
                $warna = 'bg-red-100 text-red-700';
            }
            ?>

            <div class="border rounded-2xl p-4">

                <div class="flex items-start justify-between gap-3 mb-3">

                    <div>

                        <h3 class="font-bold text-gray-800">

                            <?= $iklan['judul_iklan']; ?>

                        </h3>

                        <p class="text-sm text-gray-500 mt-1">

                            <?= $iklan['nama']; ?>

                        </p>

                    </div>

                    <span class="<?= $warna; ?> px-3 py-1 rounded-lg text-xs font-medium whitespace-nowrap">

                        <?= $iklan['status']; ?>

                    </span>

                </div>

                <div class="space-y-2 text-sm">

                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Jenis
                        </span>

                        <span class="font-medium text-right">
                            <?= $iklan['nama_jenis']; ?>
                        </span>

                    </div>

                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Harga
                        </span>

                        <span class="font-bold text-blue-600 text-right">
                            Rp <?= number_format($iklan['harga']); ?>
                        </span>

                    </div>

                </div>

            </div>

            <?php endwhile; ?>

        </div>

    </div>

</div>

<script>

const ctx =
document.getElementById('chartIklan');

new Chart(ctx, {

    type: 'line',

    data: {

        labels:
        <?= json_encode($bulan); ?>,

        datasets: [{

            label: 'Jumlah Order',

            data:
            <?= json_encode($totalIklan); ?>,

            borderWidth: 3,

            tension: 0.3,

            fill: false
        }]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false
    }
});

</script>

<?php include "../layouts/footer.php"; ?>

</body>
</html>