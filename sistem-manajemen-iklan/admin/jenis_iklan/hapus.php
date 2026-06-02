<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];

mysqli_query($conn,
    "DELETE FROM jenis_iklan
     WHERE id='$id'"
);

echo "<script>
        alert('Jenis iklan berhasil dihapus');
        window.location='index.php';
      </script>";