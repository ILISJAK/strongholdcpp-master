<?php
include 'api_functions.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$game_id = $_GET['gameId'] ?? null;

header("Content-Type: application/json");

try {
    $db = Database::getConnection();

    if ($game_id !== null) {
        // Load the specific game
        $response = load_game($user_id, $game_id);
        echo $response;
        error_log("Game loaded successfully for User ID: {$user_id}");
    } else {
        // Fetch the list of saved games
        $stmt = $db->prepare("SELECT id, save_date FROM saved_games WHERE user_id = :user_id ORDER BY save_date DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $savedGames = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($savedGames);
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
}
?>