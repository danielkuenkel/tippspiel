<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';

if ($stmt = $mysqli->prepare("SELECT * FROM games ORDER BY timestamp")) {
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($id, $timestamp, $location, $participator_a, $participator_b, $goals_participator_a, $goals_participator_b, $goals_penalty_participator_a, $goals_penalty_participator_b, $tv, $round);

    while ($stmt->fetch()) {
        $games[] = array('id' => $id, 'timestamp' => strtotime($timestamp), 'location' => $location, 'participatorA' => $participator_a, 'participatorB' => $participator_b, 'goalsParticipatorA' => $goals_participator_a, 'goalsParticipatorB' => $goals_participator_b, 'goalsPenaltyParticipatorA' => $goals_penalty_participator_a, 'goalsPenaltyParticipatorB' => $goals_penalty_participator_b, 'tv' => $tv, 'round' => $round);
    }
}

if ($stmtCountries = $mysqli->prepare("SELECT * FROM countries ORDER BY name")) {
    $stmtCountries->execute();    // Execute the prepared query.
    $stmtCountries->store_result();

    // get variables from result.
    $stmtCountries->bind_result($iso, $name, $group);

    while ($stmtCountries->fetch()) {
        $countries[] = array('iso' => $iso, 'name' => utf8_encode($name), 'group' => $group);
    }
}

if ($games && $countries) {
    echo json_encode(array('status' => 'success', 'countries' => $countries, 'games' => $games));
} else {
    echo json_encode(array('status' => 'requestError'));
}