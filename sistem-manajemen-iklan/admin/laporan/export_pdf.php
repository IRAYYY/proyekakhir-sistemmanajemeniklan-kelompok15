<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

require '../../vendor/autoload.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn,"
    SELECT
        iklan.*,
        users.nama,
        jenis_iklan.nama_jenis
    FROM iklan
    JOIN users
    ON iklan.user_id = users.id
    JOIN jenis_iklan
    ON iklan.jenis_iklan_id = jenis_iklan.id
    WHERE iklan.status != 'Belum Dibayar'
");

$html = '
<h1>Laporan Iklan</h1>

<table border="1" width="100%" cellpadding="8" cellspacing="0">

<tr>
    <th>Invoice</th>
    <th>User</th>
    <th>Judul</th>
    <th>Jenis</th>
    <th>Harga</th>
    <th>Status</th>
</tr>
';

while($d = mysqli_fetch_assoc($query)) {

    $html .= '
    <tr>

        <td>'.$d['kode_pembayaran'].'</td>
        <td>'.$d['nama'].'</td>
        <td>'.$d['judul_iklan'].'</td>
        <td>'.$d['nama_jenis'].'</td>
        <td>Rp '.number_format($d['harga']).'</td>
        <td>'.$d['status'].'</td>

    </tr>
    ';
}

$html .= '</table>';

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("laporan.pdf", [
    "Attachment" => false
]);