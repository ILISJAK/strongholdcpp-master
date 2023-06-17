<?php
session_start();

header('Content-Type: application/json');

echo json_encode(
    array(
        "turnNumber" => $_SESSION['currentTurnNumber'] ?? 1,
        "playerIdentifier" => $_SESSION['currentPlayerIdentifier'] ?? 'A'
    )
);
?>