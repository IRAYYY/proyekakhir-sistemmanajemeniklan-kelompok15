<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function getMailer() {

    $mail = new PHPMailer(true);

    // SMTP
    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'EMAILKAMU@gmail.com';

    $mail->Password = 'APP_PASSWORD_GMAIL';

    $mail->SMTPSecure = 'tls';

    $mail->Port = 587;

    // UTF
    $mail->CharSet = 'UTF-8';

    // SENDER
    $mail->setFrom(
        'EMAILKAMU@gmail.com',
        'Sistem Manajemen Iklan'
    );

    return $mail;
}