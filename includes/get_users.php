<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';

if ($stmt = $mysqli->prepare("SELECT id, username, has_payed, type FROM users")) {
    if (!$stmt->execute()) {
        echo json_encode(array('status' => 'requestError'));
        exit;
    } else {
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hasPayed, $type);

        while ($stmt->fetch()) {
            $users[] = array('id' => $id, 'username' => $username, 'hasPayed' => $hasPayed, 'type' => $type);
        }
        echo json_encode(array('status' => 'success', 'users' => $users));
    }
} else {
    echo json_encode(array('status' => 'requestError'));
}