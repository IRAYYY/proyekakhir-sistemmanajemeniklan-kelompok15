<?php
session_start();

/** @var mysqli $conn **/

include "../../config/koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

    header("Location: ../../login.php");
    exit;
}

// CEK ID
if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// AMBIL DATA USER
$query = mysqli_query($conn,
    "SELECT *
     FROM users
     WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);

// JIKA USER TIDAK ADA
if (!$user) {

    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'User tidak ditemukan'
    ];

    header("Location: index.php");
    exit;
}

// CEGAH ADMIN HAPUS DIRI SENDIRI
if ($user['id'] == $_SESSION['id']) {

    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Tidak dapat menghapus akun sendiri'
    ];

    header("Location: index.php");
    exit;
}

/**
  
 * OPTIONAL:
 * Hapus semua data iklan milik user
  
 */

mysqli_query($conn,
    "DELETE FROM iklan
     WHERE user_id='$id'"
);

/**
  
 * OPTIONAL:
 * Hapus semua notifikasi user
  
 */

mysqli_query($conn,
    "DELETE FROM notifications
     WHERE user_id='$id'"
);

/**
  
 * HAPUS FOTO PROFIL
  
 */

if (!empty($user['foto'])) {

    $fotoPath =
    "../../assets/uploads/profil/" . $user['foto'];

    if (file_exists($fotoPath)) {

        unlink($fotoPath);
    }
}

/**
  
 * HAPUS USER
  
 */

$delete = mysqli_query($conn,
    "DELETE FROM users
     WHERE id='$id'"
);

// HASIL
if ($delete) {

    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'User berhasil dihapus'
    ];

} else {

    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Gagal menghapus user'
    ];
}

// REDIRECT
header("Location: index.php");
exit;
?>