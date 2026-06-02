<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";
require_once "../../helpers/notifications.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];
$user_id = $_SESSION['id'];

// DATA IKLAN
$query = mysqli_query($conn,
    "SELECT iklan.*,
            jenis_iklan.nama_jenis
     FROM iklan
     JOIN jenis_iklan
     ON iklan.jenis_iklan_id = jenis_iklan.id
     WHERE iklan.id='$id'
     AND iklan.user_id='$user_id'
     AND iklan.status='Belum Dibayar'"
);

$data = mysqli_fetch_assoc($query);

// VALIDASI
if (!$data) {

    header("Location: ../kelola_iklan/index.php");
    exit;
}

// METODE PEMBAYARAN
$queryMetode = mysqli_query($conn,
    "SELECT *
     FROM metode_pembayaran
     ORDER BY id DESC"
);

// SUBMIT PEMBAYARAN
if (isset($_POST['submit'])) {

    $metode_id = $_POST['metode_pembayaran'];

    $bukti = NULL;

    // UPLOAD BUKTI
    if ($_FILES['bukti']['name'] != '') {

        $namaFile =
            time() . '_' . $_FILES['bukti']['name'];

        move_uploaded_file(
            $_FILES['bukti']['tmp_name'],
            "../../assets/uploads/bukti_pembayaran/" . $namaFile
        );

        $bukti = $namaFile;
    }

    // UPDATE DATABASE
    mysqli_query($conn,
        "UPDATE iklan SET

            metode_pembayaran_id='$metode_id',
            bukti_pembayaran='$bukti',
            tanggal_pembayaran=NOW(),
            status='Pending'

        WHERE id='$id'"
    );

    // NOTIF USER
    createNotification(

        $conn,

        $_SESSION['id'],

        'Pembayaran Berhasil Dikirim',

        'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.',

        'user'
    );

    // AMBIL SEMUA ADMIN
    $getAdmin = mysqli_query($conn,
        "SELECT id
         FROM users
         WHERE role='admin'"
    );

    // LOOP ADMIN
    while($admin = mysqli_fetch_assoc($getAdmin)) {

        createNotification(

            $conn,

            $admin['id'],

            'Orderan Iklan Baru',

            'Orderan iklan baru dengan kode invoice ' .
            $data['kode_pembayaran'] .
            ' memerlukan verifikasi pembayaran.',

            'admin'
        );
    }

    echo "<script>
            alert('Pembayaran berhasil dikirim');
            window.location='../kelola_iklan/index.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<?php include "../layouts/header.php"; ?>

<?php include "../layouts/sidebar.php"; ?>

<?php include "../layouts/topbar.php"; ?>

<?php include "../layouts/toast.php"; ?>

<?php include "../layouts/loading.php"; ?>

<?php include "../layouts/modal.php"; ?>

<!-- MAIN CONTENT -->
<div class="lg:ml-72 pt-24 p-4 sm:p-6">

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- DETAIL IKLAN -->
        <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl shadow">

            <div class="mb-6">

                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">

                    Detail Pembayaran

                </h2>

                <p class="text-sm text-gray-500 mt-2">

                    Periksa detail invoice sebelum mengirim pembayaran

                </p>

            </div>

            <div class="space-y-5">

                <!-- INVOICE -->
                <div class="border rounded-xl p-4">

                    <p class="text-gray-500 text-sm mb-1">
                        Invoice
                    </p>

                    <p class="font-bold text-base sm:text-lg break-all">

                        <?= $data['kode_pembayaran']; ?>

                    </p>

                </div>

                <!-- JUDUL -->
                <div class="border rounded-xl p-4">

                    <p class="text-gray-500 text-sm mb-1">
                        Judul Iklan
                    </p>

                    <p class="font-semibold text-gray-800">

                        <?= $data['judul_iklan']; ?>

                    </p>

                </div>

                <!-- JENIS -->
                <div class="border rounded-xl p-4">

                    <p class="text-gray-500 text-sm mb-1">
                        Jenis Iklan
                    </p>

                    <p class="font-semibold text-gray-800">

                        <?= $data['nama_jenis']; ?>

                    </p>

                </div>

                <!-- RINCIAN -->
                <div class="bg-gray-50 border rounded-2xl p-5">

                    <h3 class="font-bold text-lg mb-4 text-gray-800">

                        Rincian Harga

                    </h3>

                    <div class="space-y-3">

                        <div class="flex justify-between items-center gap-4">

                            <span class="text-gray-600 text-sm sm:text-base">
                                Harga Iklan
                            </span>

                            <span class="font-bold text-yellow-600 text-sm sm:text-base">

                                Rp <?= number_format($data['harga_dasar']); ?>

                            </span>

                        </div>

                        <div class="flex justify-between items-center gap-4">

                            <span class="text-gray-600 text-sm sm:text-base">
                                Harga Per Hari
                            </span>

                            <span class="font-bold text-green-600 text-sm sm:text-base">

                                Rp <?= number_format($data['harga_per_hari']); ?>

                            </span>

                        </div>

                        <div class="flex justify-between items-center gap-4">

                            <span class="text-gray-600 text-sm sm:text-base">
                                Jumlah Hari
                            </span>

                            <span class="font-bold text-blue-600 text-sm sm:text-base">

                                <?= $data['jumlah_hari']; ?> Hari

                            </span>

                        </div>

                    </div>

                </div>

                <!-- TOTAL -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">

                    <p class="text-blue-600 text-sm mb-2">
                        Total Pembayaran
                    </p>

                    <p class="text-2xl sm:text-4xl font-bold text-blue-700 break-words">

                        Rp <?= number_format($data['harga']); ?>

                    </p>

                </div>

            </div>

        </div>

        <!-- FORM PEMBAYARAN -->
        <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl shadow">

            <div class="mb-6">

                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">

                    Upload Pembayaran

                </h2>

                <p class="text-sm text-gray-500 mt-2">

                    Upload bukti pembayaran untuk verifikasi admin

                </p>

            </div>

            <form method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">

                <!-- METODE -->
                <div>

                    <label class="block mb-2 font-semibold text-sm sm:text-base">
                        Metode Pembayaran
                    </label>

                    <select name="metode_pembayaran"
                            id="metode"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base"
                            required>

                        <option value="">
                            -- Pilih Metode --
                        </option>

                        <?php while($metode = mysqli_fetch_assoc($queryMetode)) : ?>

                            <option
                                value="<?= $metode['id']; ?>"

                                data-foto="<?= $metode['foto']; ?>"

                                data-deskripsi="<?= htmlspecialchars($metode['deskripsi']); ?>">

                                <?= $metode['judul']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- DETAIL METODE -->
                <div id="detailMetode"
                     class="hidden border rounded-2xl p-4 sm:p-5 bg-gray-50">

                    <img id="fotoMetode"
                         class="w-full max-w-xs rounded-xl mb-4 hidden object-cover">

                    <div id="deskripsiMetode"
                         class="text-gray-700 whitespace-pre-line text-sm sm:text-base leading-relaxed"></div>

                </div>

                <!-- BUKTI -->
                <div>

                    <label class="block mb-2 font-semibold text-sm sm:text-base">
                        Upload Bukti Pembayaran
                    </label>

                    <input type="file"
                           name="bukti"
                           accept="image/*"
                           class="w-full border border-gray-300 p-3 rounded-xl text-sm sm:text-base"
                           required>

                    <p class="text-xs text-gray-500 mt-2">

                        Format yang didukung: JPG, PNG, JPEG

                    </p>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                        name="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-6 py-3 rounded-xl text-sm sm:text-base">

                    Kirim Pembayaran

                </button>

            </form>

        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>

<script>

const metode =
document.getElementById('metode');

const detail =
document.getElementById('detailMetode');

const foto =
document.getElementById('fotoMetode');

const deskripsi =
document.getElementById('deskripsiMetode');

metode.addEventListener('change', function() {

    const selected =
        this.options[this.selectedIndex];

    const fotoData =
        selected.getAttribute('data-foto');

    const deskripsiData =
        selected.getAttribute('data-deskripsi');

    if (!this.value) {

        detail.classList.add('hidden');

        return;
    }

    detail.classList.remove('hidden');

    // FOTO
    if (fotoData && fotoData != 'null') {

        foto.src =
            "../../assets/uploads/metode_pembayaran/" +
            fotoData;

        foto.classList.remove('hidden');
    }

    else {

        foto.classList.add('hidden');
    }

    // DESKRIPSI
    deskripsi.innerHTML =
        deskripsiData;
});

</script>

</body>
</html>