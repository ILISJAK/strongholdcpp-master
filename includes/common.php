<?php

include 'database.php';

function fetchLeaderboardData()
{
    try {
        $db = Database::getConnection();

        // Select data from the highscores table and join it with the users table
        $stmt = $db->prepare("SELECT users.username, highscores.save_date, highscores.score 
                              FROM highscores 
                              JOIN users ON highscores.user_id = users.id 
                              ORDER BY highscores.score DESC;");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            error_log("No data found in highscores table.");
        }

        return $results;

    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return null;
    }
}