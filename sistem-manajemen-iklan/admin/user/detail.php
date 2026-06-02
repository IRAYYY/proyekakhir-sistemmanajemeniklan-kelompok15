<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
    exit;
}

// VALIDASI ID
if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// QUERY USER
$queryUser = mysqli_query($conn,
    "SELECT *

     FROM users

     WHERE id='$id'"
);

$user = mysqli_fetch_assoc($queryUser);

if (!$user) {

    header("Location: index.php");
    exit;
}

// QUERY IKLAN USER
$queryIklan = mysqli_query($conn,
    "SELECT

        iklan.*,
        jenis_iklan.nama_jenis

    FROM iklan

    JOIN jenis_iklan
    ON iklan.jenis_iklan_id =
       jenis_iklan.id

    WHERE iklan.user_id='$id'

    ORDER BY iklan.id DESC"
);

// TOTAL ORDER
$totalOrder = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM iklan
         WHERE user_id='$id'"
    )
);

// TOTAL PENGELUARAN
$totalPengeluaran = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COALESCE(SUM(harga),0)
         as total
         FROM iklan
         WHERE user_id='$id'"
    )
);

// TOTAL AKTIF
$totalAktif = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM iklan
         WHERE user_id='$id'
         AND status='Aktif'"
    )
);
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Detail User
    </title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>

<!-- SIDEBAR -->
<div id="sidebar"
     class="fixed top-0 left-0 z-50 w-72 h-full bg-white shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <?php include "../layouts/sidebar.php"; ?>

</div>

<!-- OVERLAY -->
<div id="overlay"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

<!-- TOPBAR -->
<div class="fixed top-0 left-0 lg:left-72 right-0 h-20 bg-white shadow-sm z-30">

    <div class="h-full px-4 lg:px-8 flex items-center justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-4">

            <!-- BURGER -->
            <button id="menuButton"
                    class="lg:hidden w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">

                <i class="fa-solid fa-bars text-gray-700"></i>

            </button>

            <div>

                <h2 class="text-xl lg:text-2xl font-bold text-gray-800">

                    Detail User

                </h2>

                <p class="text-sm text-gray-500 hidden sm:block">

                    Informasi lengkap user platform

                </p>

            </div>

        </div>

        <!-- RIGHT -->
        <a href="index.php"
           class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl flex items-center gap-2">

            <i class="fa-solid fa-arrow-left"></i>

            <span class="hidden sm:block">

                Kembali

            </span>

        </a>

    </div>

</div>

<?php include "../layouts/toast.php"; ?>
<?php include "../layouts/loading.php"; ?>
<?php include "../layouts/modal.php"; ?>

<!-- CONTENT -->
<div class="lg:ml-72 pt-24 p-4 lg:p-8">

    <!-- USER PROFILE -->
    <div class="bg-white rounded-3xl shadow-sm p-6 lg:p-10 mb-8">

        <div class="flex flex-col lg:flex-row lg:items-center gap-8">

            <!-- FOTO -->
            <div>

                <?php if($user['foto']) : ?>

                    <img src="../../assets/uploads/profil/<?= $user['foto']; ?>"
                         class="w-32 h-32 rounded-3xl object-cover border-4 border-blue-100">

                <?php else : ?>

                    <div class="w-32 h-32 rounded-3xl bg-blue-100 flex items-center justify-center">

                        <i class="fa-solid fa-user text-blue-600 text-5xl"></i>

                    </div>

                <?php endif; ?>

            </div>

            <!-- INFO -->
            <div class="flex-1">

                <h2 class="text-3xl lg:text-4xl font-black text-gray-800">

                    <?= $user['nama']; ?>

                </h2>

                <p class="text-lg text-gray-500 mt-3">

                    <?= $user['email']; ?>

                </p>

                <div class="flex flex-wrap gap-4 mt-6">

                    <span class="bg-blue-100 text-blue-600 px-5 py-2 rounded-2xl font-semibold">

                        Client User

                    </span>

                    <span class="bg-green-100 text-green-600 px-5 py-2 rounded-2xl font-semibold">

                        Active Member

                    </span>

                </div>

            </div>

        </div>

    </div>

    <!-- STATS -->
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        <!-- TOTAL ORDER -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500 text-sm">

                        Total Order

                    </p>

                    <h2 class="text-4xl font-black text-indigo-600 mt-2">

                        <?= $totalOrder['total']; ?>

                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center">

                    <i class="fa-solid fa-cart-shopping text-indigo-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- TOTAL PENGELUARAN -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500 text-sm">

                        Total Pengeluaran

                    </p>

                    <h2 class="text-3xl font-black text-green-600 mt-2">

                        Rp <?= number_format($totalPengeluaran['total']); ?>

                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center">

                    <i class="fa-solid fa-wallet text-green-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- IKLAN AKTIF -->
        <div class="bg-white rounded-3xl p-6 shadow-sm">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500 text-sm">

                        Iklan Aktif

                    </p>

                    <h2 class="text-4xl font-black text-blue-600 mt-2">

                        <?= $totalAktif['total']; ?>

                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <i class="fa-solid fa-bullhorn text-blue-600 text-2xl"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- RIWAYAT IKLAN -->
    <div class="bg-white rounded-3xl shadow-sm p-4 lg:p-8">

        <!-- HEADER -->
        <div class="mb-8">

            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">

                Riwayat Iklan User

            </h2>

            <p class="text-gray-500 mt-2">

                Seluruh transaksi dan iklan user

            </p>

        </div>

        <!-- MOBILE CARD -->
        <div class="lg:hidden space-y-5">

            <?php while($data = mysqli_fetch_assoc($queryIklan)) : ?>

                <?php

                $warna = '';

                if ($data['status'] == 'Belum Dibayar') {

                    $warna = 'bg-red-100 text-red-600';

                } elseif ($data['status'] == 'Pending') {

                    $warna = 'bg-orange-100 text-orange-600';

                } elseif ($data['status'] == 'Proses') {

                    $warna = 'bg-yellow-100 text-yellow-600';

                } elseif ($data['status'] == 'Aktif') {

                    $warna = 'bg-green-100 text-green-600';

                } else {

                    $warna = 'bg-gray-100 text-gray-600';
                }

                ?>

                <div class="border border-gray-100 rounded-3xl p-5 shadow-sm">

                    <div class="flex justify-between items-start mb-5">

                        <div>

                            <h3 class="font-bold text-lg text-gray-800">

                                <?= $data['kode_pembayaran']; ?>

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                <?= $data['nama_jenis']; ?>

                            </p>

                        </div>

                        <span class="<?= $warna; ?> px-3 py-1 rounded-xl text-xs font-semibold">

                            <?= $data['status']; ?>

                        </span>

                    </div>

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Harga
                            </span>

                            <span class="font-bold text-green-600">

                                Rp <?= number_format($data['harga']); ?>

                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Tanggal
                            </span>

                            <span class="font-medium text-gray-700">

                                <?= date('d M Y', strtotime($data['created_at'])); ?>

                            </span>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

        <!-- DESKTOP TABLE -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead>

                    <tr class="bg-gray-100 text-gray-700">

                        <th class="p-5 text-left rounded-l-2xl">

                            Invoice

                        </th>

                        <th class="p-5 text-left">

                            Jenis Iklan

                        </th>

                        <th class="p-5 text-left">

                            Harga

                        </th>

                        <th class="p-5 text-left">

                            Status

                        </th>

                        <th class="p-5 text-left rounded-r-2xl">

                            Tanggal

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php
                mysqli_data_seek($queryIklan, 0);

                while($data = mysqli_fetch_assoc($queryIklan)) :
                ?>

                    <?php

                    $warna = '';

                    if ($data['status'] == 'Belum Dibayar') {

                        $warna = 'bg-red-100 text-red-600';

                    } elseif ($data['status'] == 'Pending') {

                        $warna = 'bg-orange-100 text-orange-600';

                    } elseif ($data['status'] == 'Proses') {

                        $warna = 'bg-yellow-100 text-yellow-600';

                    } elseif ($data['status'] == 'Aktif') {

                        $warna = 'bg-green-100 text-green-600';

                    } else {

                        $warna = 'bg-gray-100 text-gray-600';
                    }

                    ?>

                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- INVOICE -->
                        <td class="p-5 font-semibold text-gray-800">

                            <?= $data['kode_pembayaran']; ?>

                        </td>

                        <!-- JENIS -->
                        <td class="p-5">

                            <?= $data['nama_jenis']; ?>

                        </td>

                        <!-- HARGA -->
                        <td class="p-5 font-bold text-green-600">

                            Rp <?= number_format($data['harga']); ?>

                        </td>

                        <!-- STATUS -->
                        <td class="p-5">

                            <span class="<?= $warna; ?> px-4 py-2 rounded-xl text-sm font-semibold">

                                <?= $data['status']; ?>

                            </span>

                        </td>

                        <!-- TANGGAL -->
                        <td class="p-5 text-gray-600">

                            <?= date('d M Y', strtotime($data['created_at'])); ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

<!-- JAVASCRIPT -->
<script>

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuButton = document.getElementById('menuButton');

    menuButton.addEventListener('click', () => {

        sidebar.classList.remove('-translate-x-full');

        overlay.classList.remove('hidden');

    });

    overlay.addEventListener('click', () => {

        sidebar.classList.add('-translate-x-full');

        overlay.classList.add('hidden');

    });

</script>

</body>
</html>