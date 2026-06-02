<?php
/** @var mysqli $conn **/

session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
}

$user_id = $_SESSION['id'];

// FILTER
$filter = isset($_GET['filter'])
    ? $_GET['filter']
    : 'all';

// QUERY
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
<html>
<head>

    <title>Notifikasi</title>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-gray-100">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- CONTENT -->
<div class="lg:ml-72 pt-24 lg:pt-28 p-4 lg:p-8">

    <!-- HEADER -->
    <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-5 mb-8">

        <!-- TITLE -->
        <div>

            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">

                Notifikasi

            </h1>

            <p class="text-gray-500 mt-1 text-sm lg:text-base">

                Seluruh aktivitas iklan dan pembayaran Anda

            </p>

        </div>

        <!-- FILTER BUTTON -->
        <div class="flex flex-wrap gap-3">

            <a href="?filter=all"
               class="<?= $filter == 'all'
                    ? 'bg-blue-600 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 lg:px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition text-sm lg:text-base">

                Semua

            </a>

            <a href="?filter=unread"
               class="<?= $filter == 'unread'
                    ? 'bg-orange-500 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 lg:px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition text-sm lg:text-base">

                Belum Dibaca

            </a>

            <a href="?filter=read"
               class="<?= $filter == 'read'
                    ? 'bg-green-600 text-white'
                    : 'bg-white text-gray-700'; ?>

                    px-4 lg:px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition text-sm lg:text-base">

                Sudah Dibaca

            </a>

        </div>

    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <?php if(mysqli_num_rows($query) > 0) : ?>

            <?php while($notif = mysqli_fetch_assoc($query)) : ?>

                <div class="border-b border-gray-100 hover:bg-gray-50 transition">

                    <div class="p-4 lg:p-6 flex flex-col xl:flex-row xl:justify-between xl:items-start gap-5">

                        <!-- LEFT -->
                        <div class="flex gap-4 flex-1">

                            <!-- ICON -->
                            <div class="pt-1">

                                <?php if($notif['is_read'] == '0') : ?>

                                    <div class="w-3 h-3 lg:w-4 lg:h-4 bg-red-500 rounded-full"></div>

                                <?php else : ?>

                                    <div class="w-3 h-3 lg:w-4 lg:h-4 bg-gray-300 rounded-full"></div>

                                <?php endif; ?>

                            </div>

                            <!-- CONTENT -->
                            <div class="flex-1 min-w-0">

                                <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-2 break-words">

                                    <?= $notif['title']; ?>

                                </h3>

                                <p class="text-gray-600 leading-relaxed text-sm lg:text-base break-words">

                                    <?= $notif['message']; ?>

                                </p>

                                <div class="flex items-center gap-4 mt-4">

                                    <p class="text-xs lg:text-sm text-gray-400">

                                        <i class="fa-regular fa-clock mr-1"></i>

                                        <?= date(
                                            'd M Y H:i',
                                            strtotime($notif['created_at'])
                                        ); ?>

                                    </p>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full xl:w-auto">

                            <!-- READ -->
                            <?php if($notif['is_read'] == '0') : ?>

                                <a href="read.php?id=<?= $notif['id']; ?>"
                                   class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm text-center whitespace-nowrap">

                                    Tandai Dibaca

                                </a>

                            <?php endif; ?>

                            <!-- DELETE -->
                            <a href="hapus.php?id=<?= $notif['id']; ?>"
                               onclick="return confirm('Hapus notifikasi ini?')"
                               class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-xl text-sm text-center whitespace-nowrap">

                                Hapus

                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else : ?>

            <!-- EMPTY -->
            <div class="p-10 lg:p-20 text-center">

                <div class="w-20 h-20 lg:w-24 lg:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">

                    <i class="fa-regular fa-bell text-3xl lg:text-4xl text-gray-400"></i>

                </div>

                <h3 class="text-xl lg:text-2xl font-bold text-gray-700 mb-3">

                    Belum Ada Notifikasi

                </h3>

                <p class="text-gray-500 text-sm lg:text-base">

                    Semua aktivitas iklan akan muncul di sini

                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

</body>
</html>