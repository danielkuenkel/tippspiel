<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';
session_start();

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$game_id = $_POST['gameId'];

// check if anybody wants to tip after server time
$tipTime = floatval($_POST['tipTime']);
$serverTime = microtime(true);
if ($serverTime > $tipTime) {
    $to = 'danielkuenkel@gesturenote.de';
    $subject = 'Schummelversuch';
    $message = '<html>
                        <head>
                        <title>Nutzer hat versucht zu schummeln</title>
                        </head>
                        <body>
                            <p>Der Nutzer mit dem Namen ' . $username . ' und der E-Mail-Adresse ' . $email . ' hat versucht zu schummeln.</p>
                        </body>
                        </html>';

    // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
    $header = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    // zusätzliche Header
    $header .= 'From: admin@gesturenote.de' . "\r\n";

    mail($to, $subject, $message, $header);

    echo json_encode(array('status' => 'tooLate', 'serverTime' => $serverTime, 'tipTime' => $tipTime));
    exit();
}

if ($stmt = $mysqli->prepare("SELECT id FROM tips WHERE user_id = '$user_id' AND game_id = '$game_id' LIMIT 1")) {
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($user_id);
    $stmt->fetch();

    if ($stmt->num_rows == 0) {
        // If the user exists we check if the account is locked
        // from too many login attempts 
        $user_id = $_SESSION['user_id'];
        $game_id = $_POST['gameId'];
        $goalsA = $_POST['goalsA'];
        $goalsB = $_POST['goalsB'];
        $mysqli->query("INSERT INTO tips(user_id, game_id, goals_participator_a, goals_participator_b) VALUES ('$user_id', '$game_id', '$goalsA', '$goalsB')");
        echo json_encode(array('status' => 'success', 'serverTime' => $serverTime, 'tipTime' => $tipTime, 'tooLage' => $serverTime > $tipTime));
        exit();
    } else if ($stmt->num_rows == 1) {
        // tip exists for user and game.
        echo json_encode(array('status' => 'exists'));
        exit();
    }
} else {
    echo json_encode(array('status' => 'error'));
    exit();
}
