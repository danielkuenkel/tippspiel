<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php';
include_once 'psl-config.php';
session_start();

if ($stmt = $mysqli->prepare("SELECT `id`, `participator_a`, `participator_b`, `goals_participator_a`, `goals_participator_b`, `goals_penalty_participator_a`, `goals_penalty_participator_b`, `round`, `timestamp` FROM games WHERE goals_participator_a IS NOT NULL && goals_participator_b IS NOT NULL ORDER BY timestamp")) {
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($id, $participator_a, $participator_b, $goals_participator_a, $goals_participator_b, $goals_penalty_participator_a, $goals_penalty_participator_b, $round, $timestamp);

    if ($stmt->num_rows == 0) {
        echo json_encode(array('status' => 'noResults'));
        exit();
    } else {
        while ($stmt->fetch()) {
            $games[] = array('id' => $id, 'participatorA' => $participator_a, 'participatorB' => $participator_b, 'goalsParticipatorA' => $goals_participator_a, 'goalsParticipatorB' => $goals_participator_b, 'goalsPenaltyParticipatorA' => $goals_penalty_participator_a, 'goalsPenaltyParticipatorB' => $goals_penalty_participator_b, 'round' => $round, 'timestamp' => strtotime($timestamp));
        }
    }
}

if ($userStmt = $mysqli->prepare("SELECT `id`, `username`, `has_payed` FROM users")) {
    $userStmt->execute();    // Execute the prepared query.
    $userStmt->store_result();

    // get variables from result.
    $userStmt->bind_result($id, $username, $hasPayed);

    if ($userStmt->num_rows == 0) {
        echo json_encode(array('status' => 'noUsers'));
        exit();
    } else {
        while ($userStmt->fetch()) {
            if ($hasPayed === 1) {
                $users[] = array('id' => $id, 'username' => $username);
            }
        }
    }
}

if ($stmtTips = $mysqli->prepare("SELECT * FROM tips ORDER BY user_id, game_id")) {
    $stmtTips->execute();    // Execute the prepared query.
    $stmtTips->store_result();

// get variables from result.
    $stmtTips->bind_result($id, $user_id, $game_id, $goals_participator_a, $goals_participator_b, $timestamp);

    if ($stmtTips->num_rows == 0) {
        echo json_encode(array('status' => 'noTips'));
        exit();
    } else {
        while ($stmtTips->fetch()) {
            $tips[] = array('id' => $id, 'userId' => $user_id, 'gameId' => $game_id, 'goalsParticipatorA' => $goals_participator_a, 'goalsParticipatorB' => $goals_participator_b, 'submitted' => $timestamp);
        }
    }
}

if ($stmtWinner = $mysqli->prepare("SELECT * FROM tips_winner ORDER BY user_id")) {
    $stmtWinner->execute();
    $stmtWinner->store_result();

    // get variables from result.
    $stmtWinner->bind_result($user_id, $iso);

    while ($stmtWinner->fetch()) {
        $winner_tips[] = array('userId' => $user_id, 'iso' => $iso);
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

if ($games && $tips && $users) {
    $arr = array('games' => $games, 'tips' => $tips, 'users' => $users, 'winnerTips' => $winner_tips, 'userId' => $user_id = $_SESSION['user_id'], 'countries' => $countries);
    echo json_encode($arr);
} else {
    echo json_encode(array('status' => 'requestError'));
}