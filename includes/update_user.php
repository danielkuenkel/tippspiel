<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';
include_once 'psl-config.php';

session_start();

if (isset($_SESSION['type'], $_POST['id'], $_POST['hasPayed'])) {
    $isAdmin = $_SESSION['type'] === 'admin';
    $hasPayed = $_POST['hasPayed'];
    $updateId = $_POST['id'];

    if ($isAdmin) {
        if ($select_stmt = $mysqli->prepare("SELECT email, username FROM users WHERE id = '$updateId' LIMIT 1")) {
            if (!$select_stmt->execute()) {
                echo json_encode(array('status' => 'selectError'));
                exit();
            } else {
                $select_stmt->store_result();
                $select_stmt->bind_result($email, $username);
                $select_stmt->fetch();

                if ($select_stmt->num_rows == 1) {
                    if ($update_stmt = $mysqli->prepare("UPDATE users SET has_payed = '$hasPayed' WHERE id = '$updateId'")) {
                        if (!$update_stmt->execute()) {
                            echo json_encode(array('status' => 'updateError'));
                            exit();
                        } else {
                            if ($hasPayed === 1 || $hasPayed === '1') {
                                $subject = 'Freischaltung Tippspiel WM 2018';
                                $message = '<html>
                                    <head>
                                    <title>Account freigeschaltet</title>
                                    </head>
                                    <body>
                                        <p style="font-weight: bold">Hallo ' . $username . ',</p>
                                        <p>dein Account wurde freigeschaltet! Du kannst dich nun mit deiner E-Mail-Adresse ' . $email . ' und dem gewählten Passwort unter <a href="https://gesturenote.de/tippspiel">diesem Link</a> einloggen.</p>
                                        <p>Viel Spaß und Erfolg beim Tippen, sowie eine schöne Fussball WM.</p>
                                        <p style="font-weight: bold">Viele Grüße!</p>
                                    </body>
                                    </html>';
                            } else if ($hasPayed === 0 || $hasPayed === '0') {
                                $subject = 'Sperrung Tippspiel WM 2018';
                                $message = '<html>
                                    <head>
                                    <title>Account gesperrt</title>
                                    </head>
                                    <body>
                                        <p style="font-weight: bold">Hallo ' . $username . ',</p>
                                        <p>dein Account wurde gesperrt!</p>
                                        <p>Bitte wende dich an den Administrator des Tippspiels.</p>
                                        <p style="font-weight: bold">Viele Grüße!</p>
                                    </body>
                                    </html>';
                            }

                            // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
                            $header = 'MIME-Version: 1.0' . "\r\n";
                            $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

                            // zusätzliche Header
                            $header .= 'From: admin@gesturenote.de' . "\r\n";
                            mail($email, $subject, $message, $header);

                            echo json_encode(array('status' => 'success'));
                            exit();
                        }
                    } else {
                        echo json_encode(array('status' => 'statemantError'));
                        exit();
                    }
                }
            }
        }
    } else {
        echo json_encode(array('status' => 'noAdmin'));
        exit();
    }
} else {
    echo json_encode(array('status' => 'error'));
    exit();
}