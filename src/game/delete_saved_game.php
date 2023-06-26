<?php
session_start();
include '../../includes/database.php';

// Get the JSON request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

// Getting game id from request
$game_id = $data->game_id ?? null;
$user_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in the session upon login

error_log("Delete game initiated for Game ID: {$game_id}, User ID: {$user_id}");

if ($game_id !== null && $user_id !== null) {
    try {
        $db = Database::getConnection();

        // Call the stored procedure to delete the game
        $stmt = $db->prepare("CALL DeleteGame(:game_id)");
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            error_log("Game ID: {$game_id} deleted successfully for User ID: {$user_id}");
            echo "Game deleted successfully";
        } else {
            error_log("No game found with Game ID: {$game_id} for User ID: {$user_id}");
            echo "No game found with specified ID";
        }
    } catch (PDOException $e) {
        error_log("Error deleting game for User ID: {$user_id}. Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
} else {
    error_log("No game id provided for User ID: {$user_id}");
    echo "No game id provided";
}
?>