<?php
/**
 * AUTOMATION STATUS SYSTEM
 * -----------------------------------
 * AUTO UPDATE STATUS IKLAN:
 *
 * Pending  -> tetap pending jika belum diverifikasi
 * Proses   -> Aktif (jika tanggal mulai hari ini)
 * Aktif    -> Selesai (jika tanggal selesai lewat)
 *
 * + EMAIL NOTIFICATION
 */

date_default_timezone_set('Asia/Jakarta');
include "helpers/notifications.php";
include "config/koneksi.php";

require "helpers/send_mail.php";

require "templates/email/aktif.php";

require "templates/email/selesai.php";

require "templates/email/terimakasih.php";


// TANGGAL HARI INI
$today = date('Y-m-d');

/*
|--------------------------------------------------------------------------
| 1. STATUS PROSES -> AKTIF
|--------------------------------------------------------------------------
|
| Jika:
| - status = Proses
| - tanggal_mulai <= hari ini
|
| Maka:
| - status menjadi Aktif
| - kirim email aktif
|
*/

$queryAktif = mysqli_query($conn,
    "SELECT

        iklan.*,

        users.nama,
        users.email

     FROM iklan

     JOIN users
     ON iklan.user_id = users.id

     WHERE status='Proses'
     AND tanggal_mulai <= '$today'"
);

while ($data = mysqli_fetch_assoc($queryAktif)) {

    $id = $data['id'];

    // UPDATE STATUS
    mysqli_query($conn,
        "UPDATE iklan
         SET status='Aktif'
         WHERE id='$id'"
    );

    // EMAIL AKTIF
    sendMail(
        $data['email'],
        $data['nama'],
        'Iklan Sedang Aktif',
        emailAktif(
            $data['nama'],
            $data['kode_pembayaran']
        )
    );

    // NOTIFIKASI
    createNotification(

        $conn,

        $data['user_id'],

        'Iklan Sedang Tayang',

        'Iklan "' .
        $data['judul_iklan'] .
        '" sedang aktif tayang.'

    );

    echo "Iklan ID $id -> Aktif <br>";
}

/*
|--------------------------------------------------------------------------
| 2. STATUS AKTIF -> SELESAI
|--------------------------------------------------------------------------
|
| Jika:
| - status = Aktif
| - tanggal_selesai < hari ini
|
| Maka:
| - status menjadi Selesai
| - kirim email selesai
| - kirim email terimakasih
|
*/

$querySelesai = mysqli_query($conn,
    "SELECT

        iklan.*,

        users.nama,
        users.email

     FROM iklan

     JOIN users
     ON iklan.user_id = users.id

     WHERE status='Aktif'
     AND tanggal_selesai < '$today'"
);

while ($data = mysqli_fetch_assoc($querySelesai)) {

    $id = $data['id'];

    // UPDATE STATUS
    mysqli_query($conn,
        "UPDATE iklan
         SET status='Selesai'
         WHERE id='$id'"
    );

    // EMAIL SELESAI
    sendMail(
        $data['email'],
        $data['nama'],
        'Iklan Selesai',
        emailSelesai(
            $data['nama'],
            $data['kode_pembayaran']
        )
    );

    // EMAIL TERIMA KASIH
    sendMail(
        $data['email'],
        $data['nama'],
        'Terima Kasih',
        emailTerimakasih(
            $data['nama']
        )
    );

    // NOTIFIKASI
    createNotification(

        $conn,

        $data['user_id'],

        'Iklan Selesai',

        'Iklan "' .
        $data['judul_iklan'] .
        '" telah selesai tayang. Terima kasih telah menggunakan layanan kami.'

    );

    echo "Iklan ID $id -> Selesai <br>";
}

echo "<hr>";

echo "Automation Status System berjalan sukses.";
?>