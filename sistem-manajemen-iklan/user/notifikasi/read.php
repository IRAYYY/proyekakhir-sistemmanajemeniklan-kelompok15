<?php
/** @var mysqli $conn **/
session_start();

include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {

    header("Location: ../../login.php");
}

$id = $_GET['id'];

$user_id = $_SESSION['id'];

// UPDATE
mysqli_query($conn,
    "UPDATE notifications
     SET is_read='1'
     WHERE id='$id'
     AND user_id='$user_id'"
);

// REDIRECT
header("Location: index.php");
exit;
?>