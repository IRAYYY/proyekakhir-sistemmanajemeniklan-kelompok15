<?php
/** @var mysqli $conn **/

include "../../config/koneksi.php";

$user_id = $_SESSION['id'];

// DATA USER
$queryUser = mysqli_query($conn,
    "SELECT foto, nama
     FROM users
     WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($queryUser);

// HITUNG NOTIFIKASI
$queryUnread = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM notifications
     WHERE user_id='$user_id'
     AND is_read='0'"
);

$unread = mysqli_fetch_assoc($queryUnread);

// AMBIL NOTIFIKASI
$queryNotif = mysqli_query($conn,
    "SELECT *
     FROM notifications
     WHERE user_id='$user_id'
     ORDER BY id DESC
     LIMIT 5"
);

// INISIAL USER
$inisial = strtoupper(
    substr($user['nama'], 0, 1)
);
?>

<!-- TOPBAR -->
<div class="fixed top-0 left-0 lg:left-72 right-0 h-20 bg-white shadow-sm z-30">

    <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">

        <!-- LEFT -->
        <div class="flex items-center gap-4 min-w-0">

            <!-- HAMBURGER -->
            <button id="openSidebar"
                    class="lg:hidden w-11 h-11 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center flex-shrink-0">

                <i class="fa-solid fa-bars text-gray-700 text-lg"></i>

            </button>

            <div class="min-w-0">

                <h2 class="text-lg sm:text-2xl font-bold text-gray-800 truncate">

                    User Dashboard

                </h2>

                <p class="text-xs sm:text-sm text-gray-500 truncate">

                    Selamat datang,
                    <?= $_SESSION['nama']; ?>

                </p>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">

            <!-- NOTIFICATION -->
            <div class="relative">

                <!-- BUTTON -->
                <button id="notifButton"
                        class="relative w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">

                    <i class="fa-solid fa-bell text-gray-700"></i>

                    <!-- BADGE -->
                    <?php if($unread['total'] > 0) : ?>

                        <span class="absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>

                    <?php endif; ?>

                </button>

                <!-- MOBILE OVERLAY -->
                <div id="notifOverlay"
                     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden"></div>

                <!-- DROPDOWN -->
                <div id="notifDropdown"
                     class="hidden fixed top-24 left-1/2 -translate-x-1/2 w-[94vw] max-w-sm sm:max-w-md bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden z-[60]
                            
                            lg:absolute lg:top-auto lg:left-auto lg:right-0 lg:translate-x-0 lg:w-[380px] lg:max-w-none lg:mt-4">

                    <!-- HEADER -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 flex justify-between items-center gap-3 bg-white sticky top-0 z-10">

                        <div class="min-w-0">

                            <h3 class="font-bold text-base sm:text-lg text-gray-800">

                                Notifikasi

                            </h3>

                            <p class="text-xs sm:text-sm text-gray-500">

                                <?= $unread['total']; ?>
                                belum dibaca

                            </p>

                        </div>

                        <div class="flex items-center gap-3">

                            <a href="../notifikasi/index.php"
                               class="text-blue-600 text-xs sm:text-sm font-semibold hover:underline whitespace-nowrap">

                                Lihat Semua

                            </a>

                            <!-- CLOSE MOBILE -->
                            <button id="closeNotif"
                                    class="lg:hidden w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center">

                                <i class="fa-solid fa-xmark text-gray-500"></i>

                            </button>

                        </div>

                    </div>

                    <!-- LIST -->
                    <div class="max-h-[65vh] overflow-y-auto">

                        <?php if(mysqli_num_rows($queryNotif) > 0) : ?>

                            <?php while($notif = mysqli_fetch_assoc($queryNotif)) : ?>

                                <a href="../notifikasi/read.php?id=<?= $notif['id']; ?>"
                                   class="block p-4 sm:p-5 border-b border-gray-100 hover:bg-gray-50 transition">

                                    <div class="flex gap-3 items-start">

                                        <!-- DOT -->
                                        <div class="pt-2 flex-shrink-0">

                                            <?php if($notif['is_read'] == '0') : ?>

                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>

                                            <?php else : ?>

                                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>

                                            <?php endif; ?>

                                        </div>

                                        <!-- CONTENT -->
                                        <div class="flex-1 min-w-0 overflow-hidden">

                                            <h4 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base truncate">

                                                <?= $notif['title']; ?>

                                            </h4>

                                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed break-words overflow-hidden">

                                                <?= $notif['message']; ?>

                                            </p>

                                            <p class="text-[11px] sm:text-xs text-gray-400 mt-2">

                                                <?= date(
                                                    'd M Y H:i',
                                                    strtotime($notif['created_at'])
                                                ); ?>

                                            </p>

                                        </div>

                                    </div>

                                </a>

                            <?php endwhile; ?>

                        <?php else : ?>

                            <div class="p-10 text-center">

                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">

                                    <i class="fa-regular fa-bell text-2xl text-gray-400"></i>

                                </div>

                                <p class="text-gray-500 text-sm">

                                    Belum ada notifikasi

                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <!-- PROFILE -->
            <div class="hidden sm:flex items-center gap-3">

                <?php if(!empty($user['foto'])) : ?>

                    <img src="../../assets/uploads/profil/<?= $user['foto']; ?>"
                         class="w-11 h-11 rounded-full object-cover border-2 border-blue-200 shadow-sm">

                <?php else : ?>

                    <div class="w-11 h-11 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm">

                        <?= $inisial; ?>

                    </div>

                <?php endif; ?>

                <div class="hidden md:block">

                    <p class="font-semibold text-sm text-gray-800">

                        <?= $user['nama']; ?>

                    </p>

                    <p class="text-xs text-gray-500">

                        Client

                    </p>

                </div>

            </div>

            <!-- LOGOUT -->
            <button id="logoutButton"
                    class="bg-red-500 hover:bg-red-600 transition text-white px-4 sm:px-5 py-3 rounded-xl shadow-sm">

                <i class="fa-solid fa-right-from-bracket"></i>

            </button>

        </div>

    </div>

</div>

<!-- LOGOUT MODAL -->
<div id="logoutModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-[999] p-4">

    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-[fadeIn_.3s_ease]">

        <!-- HEADER -->
        <div class="p-6 text-center">

            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">

                <i class="fa-solid fa-right-from-bracket text-3xl text-red-500"></i>

            </div>

            <h2 class="text-2xl font-bold text-gray-800">

                Konfirmasi Logout

            </h2>

            <p class="text-gray-500 mt-3 leading-relaxed">

                Apakah Anda yakin ingin keluar dari akun ini?

            </p>

        </div>

        <!-- BUTTON -->
        <div class="flex flex-col sm:flex-row gap-3 px-6 pb-6">

            <!-- BATAL -->
            <button id="cancelLogout"
                    class="w-full bg-gray-100 hover:bg-gray-200 transition text-gray-700 py-3 rounded-2xl font-semibold">

                Batal

            </button>

            <!-- LOGOUT -->
            <a href="../../logout.php"
               class="w-full bg-red-500 hover:bg-red-600 transition text-white py-3 rounded-2xl font-semibold text-center">

                Ya, Logout

            </a>

        </div>

    </div>

</div>

<style>

@keyframes fadeIn{

    from{
        opacity:0;
        transform:scale(.9);
    }

    to{
        opacity:1;
        transform:scale(1);
    }
}

</style>

<script>

// ======================
// SIDEBAR
// ======================

const sidebar =
document.getElementById('sidebar');

const sidebarOverlay =
document.getElementById('sidebarOverlay');

const openSidebar =
document.getElementById('openSidebar');

const closeSidebar =
document.getElementById('closeSidebar');

// OPEN
openSidebar.addEventListener('click', function() {

    sidebar.classList.remove('-translate-x-full');

    sidebarOverlay.classList.remove('hidden');
});

// CLOSE
function closeSidebarMenu() {

    sidebar.classList.add('-translate-x-full');

    sidebarOverlay.classList.add('hidden');
}

if(closeSidebar){

    closeSidebar.addEventListener(
        'click',
        closeSidebarMenu
    );
}

if(sidebarOverlay){

    sidebarOverlay.addEventListener(
        'click',
        closeSidebarMenu
    );
}

// ======================
// NOTIFICATION
// ======================

const notifButton =
document.getElementById('notifButton');

const notifDropdown =
document.getElementById('notifDropdown');

const notifOverlay =
document.getElementById('notifOverlay');

const closeNotif =
document.getElementById('closeNotif');

function openNotifDropdown(){

    notifDropdown.classList.remove('hidden');

    notifOverlay.classList.remove('hidden');

    document.body.style.overflow = 'hidden';
}

function closeNotifDropdown(){

    notifDropdown.classList.add('hidden');

    notifOverlay.classList.add('hidden');

    document.body.style.overflow = 'auto';
}

// TOGGLE
notifButton.addEventListener('click', function(e) {

    e.stopPropagation();

    if(notifDropdown.classList.contains('hidden')){

        openNotifDropdown();

    } else {

        closeNotifDropdown();
    }
});

// CLOSE BUTTON
if(closeNotif){

    closeNotif.addEventListener('click', closeNotifDropdown);
}

// OVERLAY
notifOverlay.addEventListener('click', closeNotifDropdown);

// CLOSE OUTSIDE DESKTOP
document.addEventListener('click', function(e) {

    if(window.innerWidth >= 1024){

        if (
            !notifDropdown.contains(e.target) &&
            !notifButton.contains(e.target)
        ) {

            notifDropdown.classList.add('hidden');
        }
    }
});

// ======================
// LOGOUT MODAL
// ======================

const logoutButton =
document.getElementById('logoutButton');

const logoutModal =
document.getElementById('logoutModal');

const cancelLogout =
document.getElementById('cancelLogout');

// OPEN MODAL
logoutButton.addEventListener('click', function() {

    logoutModal.classList.remove('hidden');

    logoutModal.classList.add('flex');
});

// CLOSE MODAL
cancelLogout.addEventListener('click', function() {

    logoutModal.classList.add('hidden');

    logoutModal.classList.remove('flex');
});

// CLOSE WHEN CLICK OUTSIDE
logoutModal.addEventListener('click', function(e) {

    if(e.target === logoutModal){

        logoutModal.classList.add('hidden');

        logoutModal.classList.remove('flex');
    }
});

</script>