<?php

if (!function_exists('createNotification')) {

    function createNotification(
        $conn,
        $user_id,
        $title,
        $message,
        $target_role = 'user'
    ) {

        $title =
        mysqli_real_escape_string($conn, $title);

        $message =
        mysqli_real_escape_string($conn, $message);

        mysqli_query($conn,
            "INSERT INTO notifications (

                user_id,
                title,
                message,
                target_role,
                is_read,
                created_at

            ) VALUES (

                '$user_id',
                '$title',
                '$message',
                '$target_role',
                '0',
                NOW()

            )"
        );
    }

}
?>