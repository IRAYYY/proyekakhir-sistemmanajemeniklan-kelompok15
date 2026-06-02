<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Invoice');
$sheet->setCellValue('B1', 'User');
$sheet->setCellValue('C1', 'Judul');
$sheet->setCellValue('D1', 'Jenis');
$sheet->setCellValue('E1', 'Harga');
$sheet->setCellValue('F1', 'Status');

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

$rowNumber = 2;

while($d = mysqli_fetch_assoc($query)) {

    $sheet->setCellValue('A'.$rowNumber, $d['kode_pembayaran']);
    $sheet->setCellValue('B'.$rowNumber, $d['nama']);
    $sheet->setCellValue('C'.$rowNumber, $d['judul_iklan']);
    $sheet->setCellValue('D'.$rowNumber, $d['nama_jenis']);
    $sheet->setCellValue('E'.$rowNumber, $d['harga']);
    $sheet->setCellValue('F'.$rowNumber, $d['status']);

    $rowNumber++;
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition: attachment;filename="laporan.xlsx"');

$writer->save('php://output');