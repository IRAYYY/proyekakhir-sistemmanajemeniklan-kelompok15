<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
    exit;
}

// PAGINATION

$limit = 5;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

if ($page < 1) {

    $page = 1;
}

$start = ($page - 1) * $limit;


$search = '';

$where = '';

if (isset($_GET['search']) && $_GET['search'] != '') {

    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );

    $where = "WHERE users.nama LIKE '%$search%'";
}

// TOTAL DATA]

$totalQuery = mysqli_query($conn,
    "SELECT COUNT(*) as total
     FROM users
     $where"
);

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPage = ceil($totalData / $limit);

 
// QUERY DATA USER
 

$query = mysqli_query($conn,
    "SELECT

        users.*,

        COUNT(iklan.id) as total_order,

        COALESCE(SUM(iklan.harga),0)
        as total_pengeluaran

    FROM users

    LEFT JOIN iklan
    ON users.id = iklan.user_id

    $where

    GROUP BY users.id

    ORDER BY users.id DESC

    LIMIT $start, $limit"
);

 
// TOTAL USER
 

$totalUser = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM users
         WHERE role='user'"
    )
);

 
// TOTAL TRANSAKSI
 

$totalOrder = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM iklan"
    )
);

 
// TOTAL REVENUE
 

$totalRevenue = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COALESCE(SUM(harga),0)
         as total
         FROM iklan"
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
        User Management
    </title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>
<?php include "../layouts/sidebar.php"; ?>
<?php include "../layouts/topbar.php"; ?>
<?php include "../layouts/toast.php"; ?>
<?php include "../layouts/loading.php"; ?>
<?php include "../layouts/modal.php"; ?>

<!-- CONTENT -->
<div class="lg:ml-72 pt-24 lg:pt-28 p-4 lg:p-8">

    <!-- PAGE HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <!-- TITLE -->
        <div>

            <h2 class="text-3xl font-bold text-gray-800">

                Kelola User

            </h2>

            <p class="text-gray-500 mt-1">

                Total <?= $totalData; ?> user ditemukan

            </p>

        </div>

        <!-- SEARCH -->
        

    </div>
   <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-sm p-4 lg:p-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

            <div>

                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">

                    Data User

                </h2>

                <p class="text-gray-500 mt-2">

                    Seluruh data user platform

                </p>

            </div>

            <!-- SEARCH -->
            <form method="GET"
                  class="w-full lg:w-auto">

                <div class="relative">

                    <input type="text"
                           name="search"
                           value="<?= $search; ?>"
                           placeholder="Cari nama user..."
                           class="w-full lg:w-[350px] border border-gray-200 pl-12 pr-4 py-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

            </form>

        </div>

        <!-- MOBILE -->
        <div class="lg:hidden space-y-5">

            <?php while($data = mysqli_fetch_assoc($query)) : ?>

                <div class="border border-gray-100 rounded-3xl p-5 shadow-sm">

                    <div class="flex items-center gap-4 mb-5">

                        <?php if($data['foto']) : ?>

                            <img src="../../assets/uploads/profil/<?= $data['foto']; ?>"
                                 class="w-16 h-16 rounded-2xl object-cover">

                        <?php else : ?>

                            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center">

                                <i class="fa-solid fa-user text-blue-600 text-xl"></i>

                            </div>

                        <?php endif; ?>

                        <div>

                            <h3 class="font-bold text-lg text-gray-800">

                                <?= $data['nama']; ?>

                            </h3>

                            <p class="text-sm text-gray-500">

                                <?= $data['email']; ?>

                            </p>

                        </div>

                    </div>

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Total Order
                            </span>

                            <span class="font-semibold text-gray-700">

                                <?= $data['total_order']; ?>

                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Total Pengeluaran
                            </span>

                            <span class="font-bold text-green-600">

                                Rp <?= number_format($data['total_pengeluaran']); ?>

                            </span>

                        </div>

                    </div>

                    <div class="flex gap-3 mt-5">

                        <a href="detail.php?id=<?= $data['id']; ?>"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-2xl flex items-center justify-center gap-2">

                            <i class="fa-solid fa-eye"></i>

                            Detail

                        </a>

                        <button
                            onclick="openDeleteModal(
                                'delete.php?id=<?= $data['id']; ?>',
                                '<?= htmlspecialchars($data['nama']); ?>'
                            )"
                            class="bg-red-500 hover:bg-red-600 transition text-white px-5 rounded-2xl">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

        <!-- DESKTOP -->
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead>

                    <tr class="bg-gray-100 text-gray-700">

                        <th class="p-5 text-left rounded-l-2xl">

                            User

                        </th>

                        <th class="p-5 text-left">

                            Email

                        </th>

                        <th class="p-5 text-left">

                            Total Order

                        </th>

                        <th class="p-5 text-left">

                            Total Pengeluaran

                        </th>

                        <th class="p-5 text-left rounded-r-2xl">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php
                mysqli_data_seek($query, 0);

                while($data = mysqli_fetch_assoc($query)) :
                ?>

                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- USER -->
                        <td class="p-5">

                            <div class="flex items-center gap-4">

                                <?php if($data['foto']) : ?>

                                    <img src="../../assets/uploads/profil/<?= $data['foto']; ?>"
                                         class="w-14 h-14 rounded-2xl object-cover">

                                <?php else : ?>

                                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                                        <i class="fa-solid fa-user text-blue-600"></i>

                                    </div>

                                <?php endif; ?>

                                <div>

                                    <h3 class="font-bold text-gray-800">

                                        <?= $data['nama']; ?>

                                    </h3>

                                    <p class="text-sm text-gray-500">

                                        Client User

                                    </p>

                                </div>

                            </div>

                        </td>

                        <!-- EMAIL -->
                        <td class="p-5 text-gray-700">

                            <?= $data['email']; ?>

                        </td>

                        <!-- ORDER -->
                        <td class="p-5">

                            <span class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-xl text-sm font-semibold">

                                <?= $data['total_order']; ?> Order

                            </span>

                        </td>

                        <!-- PENGELUARAN -->
                        <td class="p-5 font-bold text-green-600">

                            Rp <?= number_format($data['total_pengeluaran']); ?>

                        </td>

                        <!-- AKSI -->
                        <td class="p-5">

                            <div class="flex gap-2">

                                <a href="detail.php?id=<?= $data['id']; ?>"
                                   class="bg-blue-500 hover:bg-blue-600 transition text-white px-4 py-2 rounded-xl text-sm">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <button
                                    onclick="openDeleteModal(
                                        'delete.php?id=<?= $data['id']; ?>',
                                        '<?= htmlspecialchars($data['nama']); ?>'
                                    )"
                                    class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-xl text-sm">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- PAGINATION -->
    <?php if($totalPage > 1) : ?>

    <div class="flex flex-wrap justify-center items-center gap-2 mt-10">

        <!-- PREV -->
        <?php if($page > 1) : ?>

            <a href="?page=<?= $page - 1; ?>&search=<?= $search; ?>"
               class="px-4 py-2 rounded-xl bg-white border hover:bg-gray-100 transition">

                <i class="fa-solid fa-chevron-left"></i>

            </a>

        <?php endif; ?>

        <!-- NUMBER -->
        <?php for($i = 1; $i <= $totalPage; $i++) : ?>

            <a href="?page=<?= $i; ?>&search=<?= $search; ?>"
               class="px-5 py-2 rounded-xl font-semibold transition

               <?= $page == $i
                    ? 'bg-blue-600 text-white shadow-lg'
                    : 'bg-white border hover:bg-gray-100 text-gray-700'; ?>">

                <?= $i; ?>

            </a>

        <?php endfor; ?>

        <!-- NEXT -->
        <?php if($page < $totalPage) : ?>

            <a href="?page=<?= $page + 1; ?>&search=<?= $search; ?>"
               class="px-4 py-2 rounded-xl bg-white border hover:bg-gray-100 transition">

                <i class="fa-solid fa-chevron-right"></i>

            </a>

        <?php endif; ?>

    </div>

    <?php endif; ?>

</div>

<!-- DELETE MODAL -->
<div id="deleteModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-5">

    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-red-500 p-6 text-white text-center">

            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">

                <i class="fa-solid fa-trash text-3xl"></i>

            </div>

            <h2 class="text-2xl font-bold">
                Hapus User
            </h2>

        </div>

        <!-- BODY -->
        <div class="p-8 text-center">

            <p class="text-gray-600 text-lg leading-relaxed">

                Yakin ingin menghapus user
                <span id="deleteUserName"
                      class="font-bold text-gray-800"></span> ?

            </p>

            <p class="text-sm text-red-500 mt-3">

                Semua data terkait user akan ikut terhapus.

            </p>

            <!-- BUTTON -->
            <div class="flex gap-4 mt-8">

                <button onclick="closeDeleteModal()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 transition py-3 rounded-2xl font-semibold">

                    Batal

                </button>

                <a href="#"
                   id="deleteBtn"
                   class="flex-1 bg-red-500 hover:bg-red-600 transition text-white py-3 rounded-2xl font-semibold text-center">

                    Ya, Hapus

                </a>

            </div>

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

function openDeleteModal(url, name)
{
    document
        .getElementById('deleteModal')
        .classList.remove('hidden');

    document
        .getElementById('deleteModal')
        .classList.add('flex');

    document
        .getElementById('deleteBtn')
        .href = url;

    document
        .getElementById('deleteUserName')
        .innerText = name;
}

function closeDeleteModal()
{
    document
        .getElementById('deleteModal')
        .classList.remove('flex');

    document
        .getElementById('deleteModal')
        .classList.add('hidden');
}

document
.getElementById('deleteModal')
.addEventListener('click', function(e){

    if(e.target === this)
    {
        closeDeleteModal();
    }

});

</script>

</body>
</html>