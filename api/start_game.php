<?php
session_start();

include '../api/api_functions.php';
include '../includes/player.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_players = $_POST['numPlayers'] ?? null;
    if ($num_players !== null) {
        $_SESSION['numPlayers'] = $_POST['numPlayers'] ?? null;
        start_game($num_players);
    }
}
?>