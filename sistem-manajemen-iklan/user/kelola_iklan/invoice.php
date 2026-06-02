<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

require '../../vendor/autoload.php';

use Dompdf\Dompdf;

if ($_SESSION['role'] != 'user') {

    header("Location: ../../login.php");
}

$id = $_GET['id'];

$user_id = $_SESSION['id'];

// QUERY DATA
$query = mysqli_query($conn,
    "SELECT

        iklan.*,

        users.nama,
        users.email,
        users.no_telp,
        users.alamat,

        jenis_iklan.nama_jenis,

        metode_pembayaran.judul as metode

    FROM iklan

    JOIN users
    ON iklan.user_id = users.id

    JOIN jenis_iklan
    ON iklan.jenis_iklan_id = jenis_iklan.id

    LEFT JOIN metode_pembayaran
    ON iklan.metode_pembayaran_id =
       metode_pembayaran.id

    WHERE iklan.id='$id'
    AND iklan.user_id='$user_id'"
);

$data = mysqli_fetch_assoc($query);

// VALIDASI
if (!$data) {

    die("Data tidak ditemukan");
}

// DOMPDF
$dompdf = new Dompdf();

// HTML PDF
$html = '

<!DOCTYPE html>
<html>
<head>

<style>

body {

    font-family: Arial, sans-serif;
    color: #333;
}

.header {

    border-bottom: 3px solid #2563eb;
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.logo {

    font-size: 28px;
    font-weight: bold;
    color: #2563eb;
}

.invoice-title {

    font-size: 24px;
    font-weight: bold;
    margin-top: 10px;
    
}

.section {

    margin-bottom: 10px;
}

.table {

    width: 100%;
    border-collapse: collapse;
}

.table td {

    padding: 10px;
    border: 1px solid #ddd;
}

.label {

    width: 35%;
    background: #f3f4f6;
    font-weight: bold;
}

.status {

    display: inline-block;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: bold;
    color: white;
}

.status.pending {
    background: orange;
}

.status.proses {
    background: #eab308;
}

.status.aktif {
    background: green;
}

.status.selesai {
    background: gray;
}

.total {

    font-size: 24px;
    font-weight: bold;
    color: #2563eb;
}

.footer {

    margin-top: 20px;
    text-align: center;
    font-size: 12px;
    color: #666;
}

</style>

</head>

<body>

<div class="header">

    <div class="logo">
        Sistem Manajemen Iklan
    </div>

    <div class="invoice-title">
        INVOICE PEMBAYARAN
    </div>

</div>

<div class="section">

    <table class="table">

        <tr>
            <td class="label">Kode Invoice</td>
            <td>'.$data['kode_pembayaran'].'</td>
        </tr>

        <tr>
            <td class="label">Tanggal Pembayaran</td>
            <td>'.(
                $data['tanggal_pembayaran']
                ? $data['tanggal_pembayaran']
                : '-'
            ).'</td>
        </tr>

        <tr>
            <td class="label">Status</td>
            <td>

                <span class="status '.strtolower($data['status']).'">

                    '.$data['status'].'

                </span>

            </td>
        </tr>

    </table>

</div>

<div class="section">

    <h3>Data User</h3>

    <table class="table">

        <tr>
            <td class="label">Nama</td>
            <td>'.$data['nama'].'</td>
        </tr>

        <tr>
            <td class="label">Email</td>
            <td>'.$data['email'].'</td>
        </tr>

        <tr>
            <td class="label">Nomor Telepon</td>
            <td>'.(
                $data['no_telp']
                ? $data['no_telp']
                : '-'
            ).'</td>
        </tr>

        <tr>
            <td class="label">Alamat</td>
            <td>'.(
                $data['alamat']
                ? $data['alamat']
                : '-'
            ).'</td>
        </tr>

    </table>

</div>

<div class="section">

    <h3>Detail Iklan</h3>

    <table class="table">

        <tr>
            <td class="label">Judul Iklan</td>
            <td>'.$data['judul_iklan'].'</td>
        </tr>

        <tr>
            <td class="label">Jenis Iklan</td>
            <td>'.$data['nama_jenis'].'</td>
        </tr>

        <tr>
            <td class="label">Durasi</td>
            <td>'.$data['durasi'].' Detik</td>
        </tr>

        <tr>
            <td class="label">Tanggal Tayang</td>
            <td>

                '.$data['tanggal_mulai'].' s/d
                '.$data['tanggal_selesai'].'

            </td>
        </tr>
        <tr>
            <td class="label">Biaya Media</td>
            <td>
                Rp '.number_format($data['harga_dasar']).'
            </td>
        </tr>

        <tr>
            <td class="label">Biaya Per Hari</td>
            <td>
                Rp '.number_format($data['harga_per_hari']).'
            </td>
        </tr>

        <tr>
            <td class="label">Jumlah Hari</td>
            <td>
                '.$data['jumlah_hari'].' Hari
            </td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td>'.(
                $data['metode']
                ? $data['metode']
                : '-'
            ).'</td>
        </tr>

    </table>

</div>

<div class="section">

    <table class="table">

        <tr>
            <td class="label">Total Pembayaran</td>

            <td class="total">

                Rp '.number_format($data['harga']).'

            </td>
        </tr>

    </table>

</div>

<div class="footer">

    Invoice ini dibuat otomatis oleh sistem.<br>

    Sistem Manajemen Iklan © '.date('Y').'

</div>

</body>
</html>

';

// LOAD HTML
$dompdf->loadHtml($html);

// SET PAPER
$dompdf->setPaper('A4', 'portrait');

// RENDER
$dompdf->render();

// OUTPUT PDF
$dompdf->stream(
    "invoice-".$data['kode_pembayaran'].".pdf",
    array("Attachment" => false)
);