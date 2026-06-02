<?php

require_once __DIR__ . '/../config/mail.php';

function sendMail(
    $to,
    $nama,
    $subject,
    $body
) {

    try {

        $mail = getMailer();

        $mail->addAddress($to, $nama);

        $mail->isHTML(true);

        $mail->Subject = $subject;

        $mail->Body = $body;

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}