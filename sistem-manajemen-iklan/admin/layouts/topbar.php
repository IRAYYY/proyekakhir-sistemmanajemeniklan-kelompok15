<?php
/** @var mysqli $conn **/
include "../../config/koneksi.php";

$admin_id = $_SESSION['id'];

$queryNotif = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM notifications
     WHERE user_id='$admin_id'
     AND target_role='admin'
     AND is_read='0'"
);

$notif = mysqli_fetch_assoc($queryNotif);

$totalNotif = $notif['total'];
?>

<div class="fixed top-0 left-0 lg:left-72 right-0 h-20 bg-white shadow-sm z-30">

    <div class="h-full px-4 lg:px-8 flex items-center justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-4">

            <!-- HAMBURGER -->
            <button id="openSidebar"
                    class="lg:hidden w-11 h-11 rounded-xl bg-gray-100 hover:bg-gray-200 transition">

                <i class="fa-solid fa-bars text-gray-700"></i>

            </button>

            <div>

                <h2 class="text-lg lg:text-2xl font-bold text-gray-800 underline">

                    ADS MANAGEMENT

                </h2>

                <p class="text-xs lg:text-sm text-gray-500">

                    Selamat datang kembali,
                    <?= $_SESSION['nama']; ?>

                </p>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-3 lg:gap-5">

            <!-- NOTIFICATION -->
            <a href="../notifikasi/index.php"
               class="relative">

                <div class="w-11 h-11 lg:w-12 lg:h-12 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-blue-100 transition">

                    <i class="fa-solid fa-bell text-gray-700"></i>

                </div>

                <?php if($totalNotif > 0) : ?>

                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">

                        <?= $totalNotif; ?>

                    </span>

                <?php endif; ?>

            </a>

            <!-- PROFILE -->
            <div class="hidden sm:flex items-center gap-3">

                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">

                    <i class="fa-solid fa-user text-blue-600"></i>

                </div>

                <div>

                    <p class="font-semibold text-sm">

                        <?= $_SESSION['nama']; ?>

                    </p>

                    <p class="text-xs text-gray-500">

                        Administrator

                    </p>

                </div>

            </div>

            <!-- LOGOUT -->
            <a href="../../logout.php"
               class="bg-red-500 hover:bg-red-600 transition text-white px-4 lg:px-5 py-3 rounded-xl">

                <i class="fa-solid fa-right-from-bracket"></i>

            </a>

        </div>

    </div>

</div>

<!-- SCRIPT SIDEBAR -->
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    openBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>