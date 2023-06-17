<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests
header("Content-Type: application/json"); // Set Content-Type to JSON

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../api/api_functions.php';

// Decode JSON from request body
$requestData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_identifier = $requestData['playerIdentifier'] ?? null;
    $entity_type = $requestData['entityType'] ?? null;
    $property = $requestData['property'] ?? null;

    if ($player_identifier !== null && $entity_type !== null && $property !== null) {
        $value = get_property($player_identifier, $entity_type, $property);
        $response = ['value' => $value];
        echo json_encode($response);
        exit;
    } else {
        http_response_code(400); // Bad request
        error_log('Invalid request parameters:');
        error_log('Player Identifier: ' . $player_identifier);
        error_log('Entity Type: ' . $entity_type);
        error_log('Property: ' . $property);
    }
} else {
    http_response_code(405); // Method Not Allowed
    error_log('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
}
?>
