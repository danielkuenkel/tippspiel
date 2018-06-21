<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';
session_start();

$user_id = $_SESSION['user_id'];
$country_iso = $_POST['countryIso'];

if ($stmt = $mysqli->prepare("UPDATE tips_winner SET iso='$country_iso' WHERE user_id = '$user_id'")) {
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();

    if ($stmt->affected_rows == 1) {
        echo json_encode(array('status' => 'success'));
    } else {
        if ($mysqli->query("INSERT INTO tips_winner(user_id, iso) VALUES ('$user_id', '$country_iso')")) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }
} else {
    echo json_encode(array('status' => 'error'));
}
