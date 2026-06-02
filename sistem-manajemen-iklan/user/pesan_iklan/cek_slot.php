<?php
/** @var mysqli $conn **/
include "../../config/koneksi.php";

$jenis_iklan_id = $_GET['jenis_iklan_id'];
$tanggal_mulai  = $_GET['tanggal_mulai'];
$tanggal_selesai = $_GET['tanggal_selesai'];

$data = [];

$current = strtotime($tanggal_mulai);
$end     = strtotime($tanggal_selesai);

while ($current <= $end) {

    $tanggal = date('Y-m-d', $current);

    $query = mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM iklan
         WHERE jenis_iklan_id='$jenis_iklan_id'
         AND (
                tanggal_mulai <= '$tanggal'
                AND tanggal_selesai >= '$tanggal'
             )"
    );

    $result = mysqli_fetch_assoc($query);

    $total = (int)$result['total'];

    $sisa = 10 - $total;

    $status = 'aman';

    if ($total >= 9) {

        $status = 'penuh';

    } elseif ($total >= 6) {

        $status = 'warning';
    }

    $data[] = [
        'tanggal' => $tanggal,
        'terisi' => $total,
        'sisa' => $sisa,
        'status' => $status
    ];

    $current = strtotime('+1 day', $current);
}

echo json_encode($data);