<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php';
include_once 'psl-config.php';

session_start();

if (isset($_SESSION['type'], $_POST['id'], $_POST['date'], $_POST['location'], $_POST['participatorA'], $_POST['participatorB'], $_POST['updateGoals'], $_POST['goalsParticipatorA'], $_POST['goalsParticipatorB'], $_POST['goalsPenaltyParticipatorA'], $_POST['goalsPenaltyParticipatorB'], $_POST['round'])) {
    $isAdmin = $_SESSION['type'] === 'admin';
    $updateId = $_POST['id'];
//    $timestamp = strtotime($string);
    $date = date("Y-m-d H:i:s", $_POST['date']);
    $location = $_POST['location'];
    $participatorA = $_POST['participatorA'];
    $participatorB = $_POST['participatorB'];
    $updateGoals = $_POST['updateGoals'];
    $goalsParticipatorA = $_POST['goalsParticipatorA'];
    $goalsParticipatorB = $_POST['goalsParticipatorB'];
    $goalsPenaltyParticipatorA = $_POST['goalsPenaltyParticipatorA'];
    $goalsPenaltyParticipatorB = $_POST['goalsPenaltyParticipatorB'];
    $round = $_POST['round'];

    if ($isAdmin) {
        if ($select_stmt = $mysqli->prepare("SELECT id FROM games WHERE id = '$updateId' LIMIT 1")) {
            if (!$select_stmt->execute()) {
                echo json_encode(array('status' => 'selectError'));
                exit();
            } else {
                $select_stmt->store_result();
                $select_stmt->bind_result($id);
                $select_stmt->fetch();

                if ($select_stmt->num_rows == 1) {
                    if (($goalsPenaltyParticipatorA === 'null' || $goalsPenaltyParticipatorA === null || $goalsPenaltyParticipatorA === '') && ($goalsPenaltyParticipatorB === 'null' || $goalsPenaltyParticipatorB === null || $goalsPenaltyParticipatorB === '')) {

                        if ($updateGoals === 'yes') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', goals_participator_a = '$goalsParticipatorA', goals_participator_b = '$goalsParticipatorB', goals_penalty_participator_a = NULL, goals_penalty_participator_b = NULL, round = '$round' WHERE id = '$updateId'");
                        } else if ($updateGoals === 'no') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', round = '$round' WHERE id = '$updateId'");
                        } else if ($updateGoals === 'reset') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', goals_participator_a = NULL, goals_participator_b = NULL, goals_penalty_participator_a = NULL, goals_penalty_participator_b = NULL, round = '$round' WHERE id = '$updateId'");
                        }

//                        if ($update_stmt) {
//                            if (!$update_stmt->execute()) {
//                                echo json_encode(array('status' => 'updateError'));
//                                exit();
//                            } else {
//                                echo json_encode(array('status' => 'success', 'updateGoals' => $updateGoals));
//                                exit();
//                            }
//                        } else {
//                            echo json_encode(array('status' => 'statemantError'));
//                            exit();
//                        }
                    } else {
                        if ($updateGoals === 'yes') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', goals_participator_a = '$goalsParticipatorA', goals_participator_b = '$goalsParticipatorB', goals_penalty_participator_a = '$goalsPenaltyParticipatorA', goals_penalty_participator_b = '$goalsPenaltyParticipatorB', round = '$round' WHERE id = '$updateId'");
                        } else if ($updateGoals === 'no') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', round = '$round' WHERE id = '$updateId'");
                        } else if ($updateGoals === 'reset') {
                            $update_stmt = $mysqli->prepare("UPDATE games SET timestamp = '$date', location = '$location', participator_a = '$participatorA', participator_b = '$participatorB', goals_participator_a = NULL, goals_participator_b = NULL, goals_penalty_participator_a = NULL, goals_penalty_participator_b = NULL, round = '$round' WHERE id = '$updateId'");
                        }
                    }

                    if ($update_stmt) {
                        if (!$update_stmt->execute()) {
                            echo json_encode(array('status' => 'updateError'));
                            exit();
                        } else {
                            echo json_encode(array('status' => 'success', 'updateGoals' => $updateGoals));
                            exit();
                        }
                    } else {
                        echo json_encode(array('status' => 'statemantError'));
                        exit();
                    }
                }
            }
        }
    } else {
        echo json_encode(array('status' => 'noAdmin'));
        exit();
    }
} else {
    echo json_encode(array('status' => 'error'));
    exit();
}