<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    session_destroy();
    session_start();

    $_SESSION['currentTurnNumber'] = 1;
    $_SESSION['currentPlayerIndex'] = 0;

    if ($username && $user_id) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_id;
    }

    // Send a JSON response back
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Game reset']);
    exit();
}
?>