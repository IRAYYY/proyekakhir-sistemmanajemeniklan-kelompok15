<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!-- OVERLAY MOBILE -->
<div id="sidebarOverlay"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden">
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed left-0 top-0 w-72 h-screen bg-white shadow-xl z-50
              transform -translate-x-full lg:translate-x-0
              transition-transform duration-300 ease-in-out">

    <!-- LOGO -->
    <div class="h-20 flex items-center justify-between px-8 border-b">

        <div>

            <h1 class="text-2xl font-bold text-blue-600">
                ADS MANAJEMEN
            </h1>

            <p class="text-sm text-gray-500">
                Admin Panel
            </p>

        </div>

        <!-- CLOSE MOBILE -->
        <button id="closeSidebar"
                class="lg:hidden text-gray-500 hover:text-red-500 text-xl">

            <i class="fa-solid fa-xmark"></i>

        </button>

    </div>

    <!-- MENU -->
    <div class="p-6 overflow-y-auto h-[calc(100vh-80px)] sidebar-scroll">

        <p class="text-xs font-semibold text-gray-400 uppercase mb-4">
            Main Menu
        </p>

        <div class="space-y-2">

            <!-- DASHBOARD -->
            <a href="../dashboard/dashboard.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition
               <?= $currentPage == 'dashboard.php'
                    ? 'bg-blue-600 text-white shadow-lg'
                    : 'hover:bg-gray-100 text-gray-700'; ?>">

                <i class="fa-solid fa-chart-line"></i>

                Dashboard

            </a>

            <!-- JENIS IKLAN -->
            <a href="../jenis_iklan/index.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition hover:bg-gray-100 text-gray-700">

                <i class="fa-solid fa-layer-group"></i>

                Jenis Iklan

            </a>

            <!-- PEMBAYARAN -->
            <a href="../pembayaran/index.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition hover:bg-gray-100 text-gray-700">

                <i class="fa-solid fa-wallet"></i>

                Kelola Pembayaran

            </a>

            <!-- METODE PEMBAYARAN -->
            <a href="../metode_pembayaran/index.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition hover:bg-gray-100 text-gray-700">

                <i class="fa-solid fa-credit-card"></i>

                Metode Pembayaran

            </a>

            <!-- LAPORAN -->
            <a href="../laporan/index.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition hover:bg-gray-100 text-gray-700">

                <i class="fa-solid fa-file-lines"></i>

                Laporan

            </a>
            <a href="../user/index.php"
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition hover:bg-gray-100 text-gray-700">

                <i class="fa-solid fa-users"></i>

                Kelola User

            </a>

        </div>

    </div>

</aside>