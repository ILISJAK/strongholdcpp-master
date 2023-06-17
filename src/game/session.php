<?php
session_start();

// Fetch the raw POST data
$postData = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($postData, true);

error_log("Received town data: " . json_encode($data));

// Save town data in session
if (isset($data["playerIdentifier"])) {
    $playerIdentifier = $data["playerIdentifier"];

    // Save town data in session
    if (!isset($_SESSION["towns"])) {
        $_SESSION["towns"] = array();
    }

    // Use playerIdentifier as the key
    $_SESSION["towns"][$playerIdentifier] = array_map('json_decode', $data);

    error_log("Town data saved in session for player: {$playerIdentifier}");
} else {
    error_log("playerIdentifier not found in received data.");
}

?>