<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];

// QUERY DATA
$query = mysqli_query($conn,
    "SELECT

        iklan.*,

        users.nama,
        users.email,
        users.no_telp,
        users.alamat,
        users.foto,

        jenis_iklan.nama_jenis,

        metode_pembayaran.judul as metode,
        metode_pembayaran.foto as foto_metode,

        admin.nama as nama_admin

    FROM iklan

    JOIN users
    ON iklan.user_id = users.id

    JOIN jenis_iklan
    ON iklan.jenis_iklan_id = jenis_iklan.id

    LEFT JOIN metode_pembayaran
    ON iklan.metode_pembayaran_id =
       metode_pembayaran.id

    LEFT JOIN users as admin
    ON iklan.diverifikasi_oleh = admin.id

    WHERE iklan.id='$id'"
);

$data = mysqli_fetch_assoc($query);

// VALIDASI
if (!$data) {

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Detail Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<div class="lg:ml-72 mt-24 p-4 lg:p-5 lg:p-8">

   <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT -->
        <div class="xl:col-span-2 space-y-6">

            <!-- DETAIL IKLAN -->
            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

                    <h2 class="text-2xl lg:text-3xl font-bold">

                        Detail Iklan

                    </h2>

                    <?php

                    $warna = '';

                    if ($data['status'] == 'Belum Dibayar') {

                        $warna =
                        'bg-red-100 text-red-600';
                    }

                    elseif ($data['status'] == 'Pending') {

                        $warna =
                        'bg-orange-100 text-orange-600';
                    }

                    elseif ($data['status'] == 'Proses') {

                        $warna =
                        'bg-yellow-100 text-yellow-600';
                    }

                    elseif ($data['status'] == 'Aktif') {

                        $warna =
                        'bg-green-100 text-green-600';
                    }

                    else {

                        $warna =
                        'bg-gray-100 text-gray-600';
                    }

                    ?>

                    <span class="<?= $warna; ?> px-4 py-2 rounded-xl font-semibold">

                        <?= $data['status']; ?>

                    </span>

                </div>

                <!-- INFO -->
                <div class="grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-gray-500 mb-1">
                            Invoice
                        </p>

                        <p class="font-semibold">
                            <?= $data['kode_pembayaran']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Jenis Iklan
                        </p>

                        <p class="font-semibold">
                            <?= $data['nama_jenis']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Judul Iklan
                        </p>

                        <p class="font-semibold">
                            <?= $data['judul_iklan']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Total Pembayaran
                        </p>

                        <p class="text-2xl font-bold text-blue-600">

                            Rp <?= number_format($data['harga']); ?>

                        </p>

                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">
                            Biaya Media
                        </p>

                        <p class="font-semibold">

                            Rp <?= number_format($data['harga_dasar']); ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Harga Per Hari
                        </p>

                        <p class="font-semibold">

                            Rp <?= number_format($data['harga_per_hari']); ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Jumlah Hari
                        </p>

                        <p class="font-semibold">

                            <?= $data['jumlah_hari']; ?> Hari

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Tanggal Mulai
                        </p>

                        <p class="font-semibold">
                            <?= $data['tanggal_mulai']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 mb-1">
                            Tanggal Selesai
                        </p>

                        <p class="font-semibold">
                            <?= $data['tanggal_selesai']; ?>
                        </p>

                    </div>

                    <div class="col-span-2">

                        <p class="text-gray-500 mb-1">
                            Deskripsi
                        </p>

                        <div class="bg-gray-50 p-4 rounded-xl">

                            <?= nl2br($data['deskripsi']); ?>

                        </div>

                    </div>

                </div>

            </div>

            <!-- MEDIA IKLAN -->
            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <h2 class="text-2xl font-bold mb-6">

                    Media Iklan

                </h2>

                <?php if($data['tipe_media'] == 'foto') : ?>

                    <img src="../../assets/uploads/iklan/<?= $data['media']; ?>"
                         class="w-full rounded-xl">

                <?php else : ?>

                    <video controls
                           class="w-full rounded-xl">

                        <source src="../../assets/uploads/iklan/<?= $data['media']; ?>">

                    </video>

                <?php endif; ?>

            </div>

            <!-- BUKTI PEMBAYARAN -->
            <?php if($data['bukti_pembayaran']) : ?>

            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <h2 class="text-2xl font-bold mb-6">

                    Bukti Pembayaran

                </h2>

                <img src="../../assets/uploads/bukti_pembayaran/<?= $data['bukti_pembayaran']; ?>"
                     class="w-full rounded-xl border cursor-pointer hover:scale-[1.01] transition"
                     onclick="openModal(this.src)">

            </div>

            <?php endif; ?>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <!-- USER -->
            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <h2 class="text-2xl font-bold mb-6">

                    Data User

                </h2>

                <div class="text-center mb-6">

                    <?php if($data['foto']) : ?>

                        <img src="../../assets/uploads/profil/<?= $data['foto']; ?>"
                             class="w-24 h-24 rounded-full object-cover mx-auto">

                    <?php else : ?>

                        <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto"></div>

                    <?php endif; ?>

                </div>

                <div class="space-y-4">

                    <div>

                        <p class="text-gray-500 text-sm">
                            Nama
                        </p>

                        <p class="font-semibold">
                            <?= $data['nama']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Email
                        </p>

                        <p class="font-semibold">
                            <?= $data['email']; ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Nomor Telepon
                        </p>

                        <p class="font-semibold">

                            <?= $data['no_telp']
                                ? $data['no_telp']
                                : '-'; ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Alamat
                        </p>

                        <p class="font-semibold">

                            <?= $data['alamat']
                                ? $data['alamat']
                                : '-'; ?>

                        </p>

                    </div>

                </div>

            </div>

            <!-- PEMBAYARAN -->
            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <h2 class="text-2xl font-bold mb-6">

                    Pembayaran

                </h2>

                <div class="space-y-4">

                    <div>

                        <p class="text-gray-500 text-sm">
                            Metode Pembayaran
                        </p>

                        <p class="font-semibold">

                            <?= $data['metode']
                                ? $data['metode']
                                : '-'; ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Tanggal Pembayaran
                        </p>

                        <p class="font-semibold">

                            <?= $data['tanggal_pembayaran']
                                ? $data['tanggal_pembayaran']
                                : '-'; ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Diverifikasi Oleh
                        </p>

                        <p class="font-semibold">

                            <?= $data['nama_admin']
                                ? $data['nama_admin']
                                : 'Belum Diverifikasi'; ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-gray-500 text-sm">
                            Tanggal Verifikasi
                        </p>

                        <p class="font-semibold">

                            <?= $data['tanggal_verifikasi']
                                ? $data['tanggal_verifikasi']
                                : '-'; ?>

                        </p>

                    </div>

                </div>

            </div>

            <!-- AKSI -->
            <?php if($data['status'] == 'Pending') : ?>

            <div class="bg-white p-5 lg:p-5 lg:p-8 rounded-xl shadow">

                <h2 class="text-2xl font-bold mb-6">

                    Aksi Admin

                </h2>

                <div class="flex flex-col gap-3">

                    <a href="verifikasi.php?id=<?= $data['id']; ?>"
                       onclick="return confirm('Verifikasi pembayaran ini?')"
                       class="bg-green-600 text-white px-4 py-3 rounded-xl text-center">

                        Verifikasi Pembayaran

                    </a>

                    <a href="tolak.php?id=<?= $data['id']; ?>"
                       onclick="return confirm('Tolak pembayaran ini?')"
                       class="bg-red-600 text-white px-4 py-3 rounded-xl text-center">

                        Tolak Pembayaran

                    </a>

                </div>

            </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<!-- MODAL -->
<div id="modal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">

    <img id="modalImage"
         class="max-w-5xl max-h-[90vh] rounded-xl">

</div>

<script>

const modal =
document.getElementById('modal');

const modalImage =
document.getElementById('modalImage');

function openModal(src) {

    modal.classList.remove('hidden');

    modal.classList.add('flex');

    modalImage.src = src;
}

modal.addEventListener('click', function() {

    modal.classList.remove('flex');

    modal.classList.add('hidden');
});

</script>

<?php include "../layouts/footer.php"; ?>
</body>
</html>