<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

if (!isset($_SESSION['role'])) {

    header("Location: ../../login.php");
    exit;
}

if ($_SESSION['role'] != 'user') {

    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

/*
|--------------------------------------------------------------------------
| TOTAL IKLAN USER
|--------------------------------------------------------------------------
*/
$totalIklanQuery = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE user_id='$user_id'"
);

$totalIklan = mysqli_fetch_assoc($totalIklanQuery);

/*
|--------------------------------------------------------------------------
| IKLAN AKTIF
|--------------------------------------------------------------------------
*/
$iklanAktifQuery = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE user_id='$user_id'
     AND status='Aktif'"
);

$iklanAktif = mysqli_fetch_assoc($iklanAktifQuery);

/*
|--------------------------------------------------------------------------
| IKLAN SELESAI
|--------------------------------------------------------------------------
*/
$iklanSelesaiQuery = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE user_id='$user_id'
     AND status='Selesai'"
);

$iklanSelesai = mysqli_fetch_assoc($iklanSelesaiQuery);

/*
|--------------------------------------------------------------------------
| IKLAN PENDING
|--------------------------------------------------------------------------
*/
$iklanPendingQuery = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM iklan
     WHERE user_id='$user_id'
     AND status='Pending'"
);

$iklanPending = mysqli_fetch_assoc($iklanPendingQuery);

/*
|--------------------------------------------------------------------------
| TOTAL PENGELUARAN USER
|--------------------------------------------------------------------------
*/
$totalPengeluaranQuery = mysqli_query($conn,
    "SELECT SUM(harga) as total
     FROM iklan
     WHERE user_id='$user_id'"
);

$totalPengeluaran = mysqli_fetch_assoc($totalPengeluaranQuery);

/*
|--------------------------------------------------------------------------
| IKLAN TERBARU
|--------------------------------------------------------------------------
*/
$recentAds = mysqli_query($conn,
    "SELECT *
     FROM iklan
     WHERE user_id='$user_id'
     ORDER BY id DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard User</title>

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

    <!-- HEADER -->
    <div class="mb-8">

        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">

            Dashboard User

        </h2>

        <p class="text-gray-500 mt-2 text-sm sm:text-base">

            Selamat datang kembali,
            <?= $_SESSION['nama']; ?>

        </p>

    </div>

    <!-- CARD STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- TOTAL IKLAN -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-gray-500 text-sm">

                        Total Iklan

                    </p>

                    <h3 class="text-3xl font-bold mt-3 text-gray-800">

                        <?= $totalIklan['total']; ?>

                    </h3>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <i class="fa-solid fa-bullhorn text-blue-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- IKLAN AKTIF -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-gray-500 text-sm">

                        Iklan Aktif

                    </p>

                    <h3 class="text-3xl font-bold mt-3 text-green-600">

                        <?= $iklanAktif['total']; ?>

                    </h3>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                    <i class="fa-solid fa-circle-check text-green-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- IKLAN SELESAI -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-gray-500 text-sm">

                        Iklan Selesai

                    </p>

                    <h3 class="text-3xl font-bold mt-3 text-purple-600">

                        <?= $iklanSelesai['total']; ?>

                    </h3>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center">

                    <i class="fa-solid fa-flag-checkered text-purple-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- IKLAN PENDING -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-gray-500 text-sm">

                        Menunggu Verifikasi

                    </p>

                    <h3 class="text-3xl font-bold mt-3 text-orange-500">

                        <?= $iklanPending['total']; ?>

                    </h3>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">

                    <i class="fa-solid fa-clock text-orange-500 text-2xl"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-8">

        <!-- PENGELUARAN -->
        <div class="xl:col-span-1 bg-white rounded-2xl shadow p-6">

            <h3 class="text-xl font-bold text-gray-800 mb-5">

                Total Pengeluaran

            </h3>

            <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl p-6 text-white">

                <p class="text-sm text-blue-100">

                    Total seluruh pembayaran iklan

                </p>

                <h2 class="text-3xl sm:text-4xl font-bold mt-4 break-words">

                    Rp <?= number_format($totalPengeluaran['total'] ?? 0); ?>

                </h2>

            </div>

        </div>

        <!-- IKLAN TERBARU -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                <h3 class="text-xl font-bold text-gray-800">

                    Iklan Terbaru

                </h3>

            </div>

            <div class="space-y-4">

    <?php 
    if(mysqli_num_rows($recentAds) > 0) : 
        $counter = 0; // Tambahkan variabel pencitung data
        
        while($ads = mysqli_fetch_assoc($recentAds)) : 
            if($counter >= 2) break; // Hentikan perulangan jika sudah menampilkan 3 data
    ?>
            
            <div class="border rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center gap-4">

                <!-- MEDIA -->
                <div>
                    <?php if($ads['tipe_media'] == 'foto') : ?>
                        <img src="../../assets/uploads/iklan/<?= $ads['media']; ?>"
                             class="w-full sm:w-28 h-40 sm:h-28 object-cover rounded-xl">
                    <?php else : ?>
                        <video controls class="w-full sm:w-36 rounded-xl">
                            <source src="../../assets/uploads/iklan/<?= $ads['media']; ?>">
                        </video>
                    <?php endif; ?>
                </div>

                <!-- INFO -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-lg text-gray-800 truncate">
                        <?= $ads['judul_iklan']; ?>
                    </h4>
                    <p class="text-sm text-gray-500 mt-1">
                        <?= substr($ads['deskripsi'], 0, 80); ?>...
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-xl text-sm">
                            <?= $ads['kode_pembayaran']; ?>
                        </span>
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-xl text-sm">
                            Rp <?= number_format($ads['harga']); ?>
                        </span>
                    </div>
                </div>

                <!-- STATUS -->
                <div>
                    <?php
                    $warna = '';
                    if ($ads['status'] == 'Belum Dibayar') {
                        $warna = 'bg-red-100 text-red-600';
                    } elseif ($ads['status'] == 'Pending') {
                        $warna = 'bg-orange-100 text-orange-600';
                    } elseif ($ads['status'] == 'Proses') {
                        $warna = 'bg-yellow-100 text-yellow-600';
                    } elseif ($ads['status'] == 'Aktif') {
                        $warna = 'bg-green-100 text-green-600';
                    } else {
                        $warna = 'bg-gray-100 text-gray-600';
                    }
                    ?>
                    <span class="<?= $warna; ?> px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap">
                        <?= $ads['status']; ?>
                    </span>
                </div>

            </div>

        <?php 
            $counter++; // Naikkan hitungan setiap iterasi berhasil
        endwhile; 
        ?>

        <!-- TOMBOL LIHAT SEMUA -->
        <div class="pt-2 flex justify-end">
            <a href="../riwayat_iklan/index.php" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium w-full text-center px-5 py-2.5 rounded-xl transition duration-200 text-sm shadow-sm">
                Lihat Semua
            </a>
        </div>

    <?php else : ?>

        <div class="text-center py-10">
            <p class="text-gray-400 text-lg">
                Belum ada iklan
            </p>
        </div>

    <?php endif; ?>

</div>


        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>