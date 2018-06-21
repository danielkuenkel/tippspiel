<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';
include_once 'psl-config.php';

$error_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {

    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

    // check existing username
    $prep_stmt = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this username already exists
            $error_msg .= '<p class="error">A user with this forename already exists</p>';
            echo json_encode(array('status' => 'userExists'));
            $stmt->close();
            exit();
        }
    } else {
        $error_msg .= '<p class="error">Database error line 55</p>';
        echo json_encode(array('status' => 'databaseError'));
        $stmt->close();
        exit();
    }

    if (empty($error_msg)) {

        $password = $_POST['p'];
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')")) {
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                echo json_encode(array('status' => 'insertError'));
                exit();
            }
        }
        
        $subject = 'Registrierung Tippspiel WM 2018';
        $message = '<html>
                        <head>
                        <title>Erfolgreich registriert</title>
                        </head>
                        <body>
                            <p style="font-weight: bold">Hallo ' . $username . ',</p>
                            <p>Du hast dich mit der E-Mail-Adresse ' . $email . ' erfolgreich beim Tippspiel für die Fussball WM 2018 in Russland registriert.</p>
                            <p>Bitte warte noch, bis dein Account freigeschaltet wurde.</p>
                            <p style="font-weight: bold">Viele Grüße!</p>
                        </body>
                        </html>';

        // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // zusätzliche Header
        $header .= 'From: noreply@gesturenote.de' . "\r\n";
        $header .= 'Reply-To: admin@gesturenote.de' . "\r\n";

        mail($email, $subject, $message, $header);

        $to = 'danielkuenkel@gesturenote.de';
        $subject = 'Nutzer hat sich registriert';
        $message = '<html>
                        <head>
                        <title>Nutzer registriert</title>
                        </head>
                        <body>
                            <p>Es hat sich der Nutzer mit dem Namen ' . $username . ' und der E-Mail-Adresse ' . $email . ' registriert.</p>
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
    }
}