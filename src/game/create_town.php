<?php
include '../api/api_functions.php';
session_start();
if (!isset($_SESSION['towns'])) {
    $_SESSION['towns'] = [];
}

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Create a new town object
$town = new Town(
    $data['playerIdentifier'],
    $data['population'],
    $data['activePopulation'],
    $data['gold'],
    $data['rations'],
    $data['wood'],
    $data['stone'],
    $data['housing']
);

// Store the town object in the session
array_push($_SESSION['towns'], $town);

// Return a success message
header('Content-Type: application/json');
echo json_encode(["message" => "Town created successfully"]);
?>
