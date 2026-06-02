<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";
require_once "../../helpers/notifications.php";
if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];

// VALIDASI
$query = mysqli_query($conn,
    "SELECT *
     FROM iklan
     WHERE id='$id'
     AND status='Pending'"
);
// AMBIL USER
$getUser = mysqli_query($conn,
    "SELECT user_id, judul_iklan
     FROM iklan
     WHERE id='$id'"
);

$user = mysqli_fetch_assoc($getUser);

// NOTIF
createNotification(

    $conn,

    $user['user_id'],

    'Pembayaran Ditolak',

    'Pembayaran untuk iklan "' .
    $user['judul_iklan'] .
    '" ditolak admin. Silakan upload ulang bukti pembayaran.'

);
$data = mysqli_fetch_assoc($query);

if (!$data) {

    header("Location: index.php");
    exit;
}

// HAPUS BUKTI LAMA
if (
    $data['bukti_pembayaran'] &&
    file_exists(
        "../../assets/uploads/bukti_pembayaran/" .
        $data['bukti_pembayaran']
    )
) {

    unlink(
        "../../assets/uploads/bukti_pembayaran/" .
        $data['bukti_pembayaran']
    );
}


// RESET PEMBAYARAN
mysqli_query($conn,
    "UPDATE iklan SET

        status='Belum Dibayar',

        metode_pembayaran_id=NULL,

        bukti_pembayaran=NULL,

        tanggal_pembayaran=NULL

    WHERE id='$id'"
    
);


echo "<script>
        alert('Pembayaran ditolak');
        window.location='index.php';
      </script>";

require "../helpers/send_mail.php";

require "../../templates/email/ditolak.php";
$user = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT users.nama, users.email, iklan.kode_pembayaran
         FROM iklan
         JOIN users
         ON iklan.user_id = users.id
         WHERE iklan.id='$id'"
    )
);
sendMail(
    $user['email'],
    $user['nama'],
    'Pembayaran Ditolak',
    emailDitolak(
        $user['nama'],
        $user['kode_pembayaran']
    )
);