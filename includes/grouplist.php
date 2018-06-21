<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';

if ($stmtCountries = $mysqli->prepare("SELECT * FROM countries ORDER by `group`, `name`")) {
    $stmtCountries->execute();    // Execute the prepared query.
    $stmtCountries->store_result();

    // get variables from result.
    $stmtCountries->bind_result($iso, $name, $group);

    while ($stmtCountries->fetch()) {
        $countries[] = array('iso' => $iso, 'name' => utf8_encode($name), 'group' => $group);
    }
}

if ($countries) {
    echo json_encode(array('countries' => $countries));
} else {
    echo json_encode(array('status' => 'requestError'));
}