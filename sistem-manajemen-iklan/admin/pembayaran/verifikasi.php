<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";
require_once "../../helpers/notifications.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];

$admin_id = $_SESSION['id'];
// AMBIL USER
$getUser = mysqli_query($conn,
    "SELECT user_id, judul_iklan
     FROM iklan
     WHERE id='$id'"
);

$user = mysqli_fetch_assoc($getUser);

// CREATE NOTIF
createNotification(

    $conn,

    $user['user_id'],

    'Pembayaran Diverifikasi',

    'Pembayaran untuk iklan "' .
    $user['judul_iklan'] .
    '" berhasil diverifikasi admin.'

);

// VALIDASI DATA
$query = mysqli_query($conn,
    "SELECT *
     FROM iklan
     WHERE id='$id'
     AND status='Pending'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {

    header("Location: index.php");
    exit;
}


// UPDATE STATUS
mysqli_query($conn,
    "UPDATE iklan SET

        status='Proses',

        diverifikasi_oleh='$admin_id',

        tanggal_verifikasi=NOW()

    WHERE id='$id'"
);

echo "<script>
        alert('Pembayaran berhasil diverifikasi');
        window.location='index.php';
      </script>";

require "../../helpers/send_mail.php";

require "../../templates/email/verifikasi.php";
// AMBIL USER
$user = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT users.nama, users.email, iklan.kode_pembayaran
         FROM iklan
         JOIN users
         ON iklan.user_id = users.id
         WHERE iklan.id='$id'"
    )
);

// KIRIM EMAIL
sendMail(
    $user['email'],
    $user['nama'],
    'Pembayaran Diverifikasi',
    emailVerifikasi(
        $user['nama'],
        $user['kode_pembayaran']
    )
);