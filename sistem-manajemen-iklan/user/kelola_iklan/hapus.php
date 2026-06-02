<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
}

$id = $_GET['id'];

$user_id = $_SESSION['id'];

// VALIDASI STATUS
$query = mysqli_query($conn,
    "SELECT *
     FROM iklan
     WHERE id='$id'
     AND user_id='$user_id'"
);

$data = mysqli_fetch_assoc($query);

if ($data['status'] != 'Belum Dibayar') {

    echo "<script>
            alert('Iklan tidak dapat dihapus');
            window.location='index.php';
          </script>";

    exit;
}

// HAPUS FILE MEDIA
if (file_exists("../../assets/uploads/iklan/" . $data['media'])) {

    unlink("../../assets/uploads/iklan/" . $data['media']);
}

// HAPUS DATABASE
mysqli_query($conn,
    "DELETE FROM iklan
     WHERE id='$id'"
);

echo "<script>
        alert('Iklan berhasil dihapus');
        window.location='index.php';
      </script>";