<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';
include_once 'functions.php';

session_start();

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p'];
    echo login($email, $password, $mysqli);
} else {
    echo json_encode(array('status' => 'databaseError'));
}