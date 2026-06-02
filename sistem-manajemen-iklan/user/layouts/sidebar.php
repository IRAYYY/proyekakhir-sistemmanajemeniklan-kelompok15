<?php

$currentPage =
basename($_SERVER['PHP_SELF']);

?>

<!-- OVERLAY -->
<div id="sidebarOverlay"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed top-0 left-0 w-72 h-screen bg-white shadow-xl z-50
              transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- LOGO -->
    <div class="h-20 border-b flex items-center justify-between px-6">

        <div>

            <h1 class="text-2xl font-bold text-blue-600">

                ADVERTISE

            </h1>

            <p class="text-sm text-gray-500">

                User Panel

            </p>

        </div>

        <!-- CLOSE BUTTON MOBILE -->
        <button id="closeSidebar"
                class="lg:hidden text-gray-500 hover:text-red-500 text-2xl">

            &times;

        </button>

    </div>

    <!-- MENU -->
    <div class="p-6 space-y-2 overflow-y-auto h-[calc(100vh-80px)] sidebar-scroll">

        <p class="text-xs uppercase text-gray-400 font-semibold mb-4">

            Main Menu

        </p>

        <!-- DASHBOARD -->
        <a href="../dashboard/index.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition
           <?= $currentPage == 'index.php' &&
                strpos($_SERVER['REQUEST_URI'], 'dashboard')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 text-gray-700'; ?>">

            <i class="fa-solid fa-chart-pie"></i>

            Dashboard

        </a>

        <!-- PESAN IKLAN -->
        <a href="../pesan_iklan/index.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition
           <?= strpos($_SERVER['REQUEST_URI'], 'pesan_iklan')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 text-gray-700'; ?>">

            <i class="fa-solid fa-plus"></i>

            Pesan Iklan

        </a>

        <!-- KELOLA IKLAN -->
        <a href="../kelola_iklan/index.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition
           <?= strpos($_SERVER['REQUEST_URI'], 'kelola_iklan')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 text-gray-700'; ?>">

            <i class="fa-solid fa-folder-open"></i>

            Kelola Iklan

        </a>

        <!-- RIWAYAT -->
        <a href="../riwayat_iklan/index.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition
           <?= strpos($_SERVER['REQUEST_URI'], 'riwayat_iklan')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 text-gray-700'; ?>">

            <i class="fa-solid fa-clock-rotate-left"></i>

            Riwayat Iklan

        </a>

        <!-- PROFILE -->
        <a href="../profil/index.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition
           <?= strpos($_SERVER['REQUEST_URI'], 'profil')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 text-gray-700'; ?>">

            <i class="fa-solid fa-user"></i>

            Profil

        </a>

    </div>

</aside>