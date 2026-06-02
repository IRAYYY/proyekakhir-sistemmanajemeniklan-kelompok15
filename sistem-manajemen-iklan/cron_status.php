<?php

include "config/koneksi.php";

// TANGGAL HARI INI
$today = date('Y-m-d');


// =========================================
// PROSES → AKTIF
// =========================================

mysqli_query($conn,

    "UPDATE iklan

     SET status='Aktif'

     WHERE status='Proses'

     AND tanggal_mulai <= '$today'

     AND tanggal_selesai >= '$today'"
);


// =========================================
// AKTIF → SELESAI
// =========================================

mysqli_query($conn,

    "UPDATE iklan

     SET status='Selesai'

     WHERE status='Aktif'

     AND tanggal_selesai < '$today'"
);

echo "Automation status berhasil dijalankan.";
?>