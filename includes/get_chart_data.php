<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';
session_start();
$session_user_id = $_SESSION['user_id'];

if ($selectGamesStmt = $mysqli->prepare("SELECT * FROM games WHERE goals_participator_a IS NOT NULL && goals_participator_b IS NOT NULL ORDER BY timestamp")) {
    $selectGamesStmt->execute();    // Execute the prepared query.
    $selectGamesStmt->store_result();

    // get variables from result.
    $selectGamesStmt->bind_result($id, $timestamp, $location, $participator_a, $participator_b, $goals_participator_a, $goals_participator_b, $goals_penalty_participator_a, $goals_penalty_participator_b, $tv, $round);

    while ($selectGamesStmt->fetch()) {
        $games[] = array('id' => $id, 'timestamp' => strtotime($timestamp), 'location' => $location, 'participatorA' => $participator_a, 'participatorB' => $participator_b, 'goalsParticipatorA' => $goals_participator_a, 'goalsParticipatorB' => $goals_participator_b, 'goalsPenaltyParticipatorA' => $goals_penalty_participator_a, 'goalsPenaltyParticipatorB' => $goals_penalty_participator_b, 'tv' => $tv, 'round' => $round);
    }
}

if ($stmtCountries = $mysqli->prepare("SELECT * FROM countries")) {
    $stmtCountries->execute();    // Execute the prepared query.
    $stmtCountries->store_result();

    // get variables from result.
    $stmtCountries->bind_result($iso, $name, $group);

    while ($stmtCountries->fetch()) {
        $countries[] = array('iso' => $iso, 'name' => utf8_encode($name), 'group' => $group);
    }
}

if ($stmtTips = $mysqli->prepare("SELECT * FROM tips")) {
    $stmtTips->execute();    // Execute the prepared query.
    $stmtTips->store_result();

    // get variables from result.
    $stmtTips->bind_result($id, $user_id, $game_id, $goals_participator_a, $goals_participator_b, $submitted);

    while ($stmtTips->fetch()) {
        $tips[] = array('id' => $id, 'userId' => $user_id, 'gameId' => $game_id, 'goalsParticipatorA' => $goals_participator_a, 'goalsParticipatorB' => $goals_participator_b, 'submitted' => $submitted);
    }

//    echo json_encode(array('schedule' => $schedule));
}

if ($stmtWinner = $mysqli->prepare("SELECT * FROM tips_winner WHERE user_id = '$session_user_id' LIMIT 1")) {
    $stmtWinner->execute();
    $stmtWinner->store_result();
    $stmtWinner->bind_result($user_id, $iso);

    $stmtWinner->fetch();

    if ($stmtWinner->num_rows == 1) {
        $winnerTip[] = array('userId' => $user_id, 'iso' => $iso);
    } else {
        $winnerTip[] = array('userId' => $session_user_id, 'iso' => null);
    }
} else {
    $winnerTip[] = null;
}

if ($stmtUsers = $mysqli->prepare("SELECT id, username, has_payed FROM users ORDER BY id")) {
    $stmtUsers->execute();
    $stmtUsers->store_result();
    $stmtUsers->bind_result($user_id, $username, $hasPayed);

    if ($stmtUsers->num_rows > 0) {
        while ($stmtUsers->fetch()) {
            if ($hasPayed === 1) {
                $users[] = array('id' => $user_id, 'username' => $username);
            }
        }
    } else {
        $users[] = null;
    }
} else {
    $users[] = null;
}

if ($games && $countries) {
    $arr = array('status' => 'success', 'countries' => $countries, 'games' => $games, 'tips' => $tips, 'winnerTip' => $winnerTip, 'users' => $users);
    echo json_encode($arr);
} else {
    echo json_encode(array('status' => 'requestError'));
}