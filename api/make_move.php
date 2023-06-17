<?php
include '../api/api_functions.php';

session_start(); // Start the session at the beginning of the script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_identifier = $_POST['playerIdentifier'] ?? null;
    $action = $_POST['action'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $entity_type = $_POST['entityType'] ?? null;
    $target_player_identifier = $_POST['targetPlayerIdentifier'] ?? null;

    if ($player_identifier !== null && $action !== null) {
        $response = make_move($player_identifier, $action, $amount, $entity_type, $target_player_identifier);

        // Assuming that the response is a JSON string, you can decode it to an array
        $responseData = json_decode($response, true);

        // Log the responseData to the browser console
        error_log("make_move response data:" . $response);

        // Check if the turnNumber and playerIdentifier are present in the response data
        if (isset($responseData['currentTurn']) && isset($responseData['currentPlayer'])) {
            // Store the relevant information in the session
            $_SESSION['currentTurnNumber'] = $responseData['currentTurn'];
            $_SESSION['currentPlayerIdentifier'] = $responseData['currentPlayer'];
            error_log("Session currentTurnNumber set to: " . $_SESSION['currentTurnNumber']);
            error_log("Session currentPlayerIdentifier set to: " . $_SESSION['currentPlayerIdentifier']);
        } else {
            // Handle case where turnNumber and playerIdentifier are not present
            error_log("Keys in response data: " . implode(", ", array_keys($responseData)));

        }
    }
}
?>