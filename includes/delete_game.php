<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';
include_once 'psl-config.php';

session_start();
if (isset($_SESSION['type'], $_POST['id'])) {
    $isAdmin = $_SESSION['type'] === 'admin';
    $deleteId = $_POST['id'];

    if ($delete_stmt = $mysqli->prepare("DELETE FROM games WHERE id = '$deleteId'")) {
        if (!$delete_stmt->execute()) {
            echo json_encode(array('status' => 'deleteError'));
            exit();
        } else {
            echo json_encode(array('status' => 'success'));
            exit();
        }
    } else {
        echo json_encode(array('status' => 'statemantError'));
        exit();
    }
} else {
    echo json_encode(array('status' => 'error'));
}