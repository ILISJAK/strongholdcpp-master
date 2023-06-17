<?php
session_start();
include '../../includes/database.php';

// Getting game data from session
$turnNumber = $_SESSION['currentTurnNumber'] ?? null;
$currentPlayerIndex = $_SESSION['currentPlayerIndex'] ?? null;
$players = $_SESSION['numPlayers'] ?? null;
$towns = $_SESSION['towns'] ?? null;
$user_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in the session upon login

error_log("Save game initiated. Turn number: {$turnNumber}, Current Player Index: {$currentPlayerIndex}, User ID: {$user_id}");
error_log("Session data: ". json_encode($_SESSION));

if ($turnNumber !== null && $currentPlayerIndex !== null && $towns !== null && $user_id !== null) {
    try {
        $db = Database::getConnection();

        // town data
        $jsonTowns = json_encode($towns);

        // date and time
        $saveDate = date("Y-m-d H:i:s");

        // Insert the game data into the database
        $stmt = $db->prepare("INSERT INTO saved_games (turnNumber, currentPlayerIndex, players, towns, user_id, save_date) VALUES (:turnNumber, :currentPlayerIndex, :players, :towns, :user_id, :save_date)");
        $stmt->bindParam(':turnNumber', $turnNumber, PDO::PARAM_INT);
        $stmt->bindParam(':currentPlayerIndex', $currentPlayerIndex, PDO::PARAM_INT);
        $stmt->bindParam(':players', $players, PDO::PARAM_INT);
        $stmt->bindParam(':towns', $jsonTowns, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':save_date', $saveDate, PDO::PARAM_STR);
        $stmt->execute();

        error_log("Game saved successfully for User ID: {$user_id}");
        echo "Game saved successfully";
        error_log(json_encode($_SESSION));
    } catch (PDOException $e) {
        error_log("Error saving game for User ID: {$user_id}. Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
} else {
    error_log("No game data to save for User ID: {$user_id}");
    echo "No game data to save";
    error_log(json_encode($_SESSION));
}
?>