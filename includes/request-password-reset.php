<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';

if (isset($_POST['email'])) {
    // Sanitize and validate the data passed in
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($stmt = $mysqli->prepare('SELECT id, username, email FROM users WHERE email = ? LIMIT 1')) {
        $stmt->bind_param('s', $email);

        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($id, $username, $email);
            $stmt->fetch();

            if ($stmt->num_rows == 1) {
                $now = time();
                $str = hash('sha512', $id . $username . $email . $now);
                if ($insert_stmt = $mysqli->prepare("UPDATE users SET password_reset = '$str' WHERE id = '$id'")) {
                    if (!$insert_stmt->execute()) {
                        echo json_encode(array('status' => 'insertError'));
                        exit();
                    } else {
                        $to = $email;
                        $subject = 'Password Reset';
                        $message = '<html>
                        <head>
                        <title>Passwort zurücksetzen</title>
                        </head>
                        <body>
                            <h3>Passwort zurücksetzen</h3>
                            <p>Vergeben Sie ein neues Passwort, indem Sie auf diesen <a href=https://gesturenote.de/tippspiel/reset-password.php?h=' . $str . '>Link</a> klicken und den Anweisungen folgen.</p>
                        </body>
                        </html>';

                        // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
                        $header = 'MIME-Version: 1.0' . "\r\n";
                        $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

                        // zusätzliche Header
                        $header .= 'From: noreply@gesturenote.de' . "\r\n";
                        $header .= 'Reply-To: admin@gesturenote.de' . "\r\n";

                        mail($to, $subject, $message, $header);

                        echo json_encode(array('status' => 'success'));
                        exit();
                    }
                } else {
                    echo json_encode(array('status' => 'statementError'));
                    exit();
                }
            } else {
                echo json_encode(array('status' => 'emailDoesntExist'));
                exit();
            }
        } else {
            echo json_encode(array('status' => 'error'));
            exit();
        }
    } else {
        echo json_encode(array('status' => 'statementError'));
        exit();
    }
} else {
    echo json_encode(array('status' => 'error'));
    exit();
}    