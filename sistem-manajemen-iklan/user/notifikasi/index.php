<?php
/** @var mysqli $conn **/

session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {

    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

// FILTER
$filter = isset($_GET['filter'])
    ? $_GET['filter']
    : 'all';

// QUERY FILTER
$where = "";

if ($filter == 'unread') {

    $where = "AND is_read='0'";
}

elseif ($filter == 'read') {

    $where = "AND is_read='1'";
}

// AMBIL NOTIFIKASI
$query = mysqli_query($conn,
    "SELECT *
     FROM notifications
     WHERE user_id='$user_id'
     $where
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Notifikasi</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<!-- MAIN -->
<div class="lg:ml-72 pt-24 sm:pt-28 p-4 sm:p-6">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <!-- TITLE -->
        <div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">

                Notifikasi

            </h1>

            <p class="text-sm sm:text-base text-gray-500 mt-1">

                Seluruh aktivitas iklan dan pembayaran Anda

            </p>

        </div>

        <!-- FILTER -->
        <div class="flex flex-wrap gap-3">

            <!-- SEMUA -->
            <a href="?filter=all"
               class="<?= $filter == 'all'
                    ? 'bg-blue-600 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 sm:px-5 py-3 rounded-xl shadow-sm
                    text-sm sm:text-base
                    hover:shadow-md transition">

                Semua

            </a>

            <!-- BELUM DIBACA -->
            <a href="?filter=unread"
               class="<?= $filter == 'unread'
                    ? 'bg-orange-500 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 sm:px-5 py-3 rounded-xl shadow-sm
                    text-sm sm:text-base
                    hover:shadow-md transition">

                Belum Dibaca

            </a>

            <!-- SUDAH DIBACA -->
            <a href="?filter=read"
               class="<?= $filter == 'read'
                    ? 'bg-green-600 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 sm:px-5 py-3 rounded-xl shadow-sm
                    text-sm sm:text-base
                    hover:shadow-md transition">

                Sudah Dibaca

            </a>

        </div>

    </div>

    <!-- LIST CARD -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <?php if(mysqli_num_rows($query) > 0) : ?>

            <?php while($notif = mysqli_fetch_assoc($query)) : ?>

                <div class="border-b border-gray-100 hover:bg-gray-50 transition">

                    <div class="p-4 sm:p-6 flex flex-col lg:flex-row lg:justify-between gap-5">

                        <!-- LEFT -->
                        <div class="flex gap-4 flex-1 min-w-0">

                            <!-- STATUS -->
                            <div class="pt-2">

                                <?php if($notif['is_read'] == '0') : ?>

                                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-500 rounded-full"></div>

                                <?php else : ?>

                                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-gray-300 rounded-full"></div>

                                <?php endif; ?>

                            </div>

                            <!-- CONTENT -->
                            <div class="flex-1 min-w-0">

                                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2 break-words">

                                    <?= htmlspecialchars($notif['title']); ?>

                                </h3>

                                <p class="text-sm sm:text-base text-gray-600 leading-relaxed break-words">

                                    <?= htmlspecialchars($notif['message']); ?>

                                </p>

                                <!-- DATE -->
                                <div class="flex items-center gap-2 mt-4 text-xs sm:text-sm text-gray-400">

                                    <i class="fa-regular fa-clock"></i>

                                    <?= date(
                                        'd M Y H:i',
                                        strtotime($notif['created_at'])
                                    ); ?>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="flex flex-col sm:flex-row gap-3 lg:items-start">

                            <!-- READ -->
                            <?php if($notif['is_read'] == '0') : ?>

                                <a href="read.php?id=<?= $notif['id']; ?>"
                                   class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-3 rounded-xl text-sm text-center whitespace-nowrap">

                                    Tandai Dibaca

                                </a>

                            <?php endif; ?>

                            <!-- DELETE -->
                            <a href="hapus.php?id=<?= $notif['id']; ?>"
                               onclick="return confirm('Hapus notifikasi ini?')"
                               class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-3 rounded-xl text-sm text-center whitespace-nowrap">

                                Hapus

                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else : ?>

            <!-- EMPTY -->
            <div class="p-10 sm:p-20 text-center">

                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">

                    <i class="fa-regular fa-bell text-3xl sm:text-4xl text-gray-400"></i>

                </div>

                <h3 class="text-xl sm:text-2xl font-bold text-gray-700 mb-3">

                    Belum Ada Notifikasi

                </h3>

                <p class="text-sm sm:text-base text-gray-500">

                    Semua aktivitas iklan akan muncul di sini

                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>