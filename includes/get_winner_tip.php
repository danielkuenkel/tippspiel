<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';
session_start();

if ($stmtCountries = $mysqli->prepare("SELECT * FROM countries ORDER BY `name`")) {
    $stmtCountries->execute();
    $stmtCountries->store_result();

    // get variables from result.
    $stmtCountries->bind_result($iso, $name, $group);

    while ($stmtCountries->fetch()) {
        $countries[] = array('iso' => $iso, 'name' => utf8_encode($name), 'group' => $group);
    }
}
$userId = $_SESSION['user_id'];
if ($stmt = $mysqli->prepare("SELECT user_id, iso FROM tips_winner WHERE user_id = ? LIMIT 1")) {
    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($user_id, $iso);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        $winnerTip[] = array('iserId' => $userId, 'iso' => $iso);
    } else {
        $winnerTip[] = array('userId' => $userId, 'iso' => null);
    }
}

if ($countries) {
    echo json_encode(array('countries' => $countries, 'winnerTip' => $winnerTip));
} else {
    echo json_encode(array('status' => 'requestError'));
}