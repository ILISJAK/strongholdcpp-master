<?php
include '../includes/database.php';

$base_url = "http://localhost:8080";

function start_game($num_players)
{
    global $base_url;
    $start_game_url = $base_url . "/start-game";
    $data = array("numPlayers" => (int) $num_players);
    echo $num_players;
    echo "\nRequest data: ";
    print_r($data); // debug logging

    $curl = curl_init($start_game_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($curl);
    if (!$response) {
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    print_response($http_status, $response);

    curl_close($curl);
}

function make_move($player_identifier, $action, $amount = null, $entity_type = null, $target_player_identifier = null)
{
    global $base_url;
    $make_move_url = $base_url . "/make-move";
    $data = array("playerIdentifier" => $player_identifier, "action" => $action);

    if (!is_null($amount)) {
        $data["amount"] = (int) $amount;
    }

    if (!is_null($entity_type)) {
        if ($action == "train-troop") {
            $data["troop"] = $entity_type;
        } elseif ($action == "build-structure") {
            $data["structure"] = $entity_type;
        } elseif ($action == "buy-from-market" || $action == "sell-to-market") {
            $data["resource"] = $entity_type;
        }
    }

    if (!is_null($target_player_identifier)) {
        $data["targetPlayerIdentifier"] = $target_player_identifier;
    }

    echo "\nRequest data: ";
    print_r($data); // debug logging

    $curl = curl_init($make_move_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($curl);
    if (!$response) {
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    print_response($http_status, $response);

    curl_close($curl);

    return $response;
}

function print_response($http_status_code, $content)
{
    echo "Response HTTP Status Code: ", $http_status_code, "\n";
    echo "Response Body: ", $content, "\n";
}

function get_property($player_identifier, $entity_type, $property)
{
    global $base_url;
    $get_property_url = $base_url . "/get-property";

    $data = array(
        "playerIdentifier" => $player_identifier,
        "entityType" => $entity_type,
        "property" => $property
    );

    // echo "\nRequest data: ";
    // print_r($data); // debug logging

    $curl = curl_init($get_property_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($curl);
    if (!$response) {
        error_log('cURL Error: ' . curl_error($curl) . '<br>');
        error_log('HTTP Code: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . '<br>');
        error_log('cURL Info: ' . print_r(curl_getinfo($curl), true) . '<br>');
        die('Error Code: ' . curl_errno($curl));
    }

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    error_log("\nHTTP Status: $http_status\n");
    error_log("\nResponse:\n$response\n"); // debug logging

    curl_close($curl);

    return $response;
}

function load_game($user_id, $game_id)
{
    global $base_url;
    $load_game_url = $base_url . "/load-game";
    $response = null;

    // Fetch saved game data from the database
    try {
        $db = Database::getConnection();

        // Select the game data from the database
        $stmt = $db->prepare("SELECT * FROM saved_games WHERE user_id = :user_id AND id = :game_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();

        $savedGame = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($savedGame) {
            // Unserialize towns data
            $townsData = json_decode($savedGame['towns'], true);
            $serializedTowns = [];

            // Convert string numbers to actual numbers and set team
            foreach ($townsData as $team => &$town) {
                $townEntry = [
                    "team" => $team,
                    "activePopulation" => (int) $town['activePopulation']['activePopulation'],
                    "population" => (int) $town['population']['population'],
                    "gold" => (float) $town['gold']['gold'],
                    "rations" => (int) $town['rations']['rations'],
                    "wood" => (int) $town['wood']['wood'],
                    "stone" => (int) $town['stone']['stone'],
                    "housing" => (int) $town['housing']['housing']
                ];
                array_push($serializedTowns, $townEntry);
            }

            // Prepare the data for the API
            $dataToSend = [
                "serializedTowns" => json_encode($serializedTowns)
            ];

            // Log the data being sent to the server
            error_log("Sending data to API:\n" . json_encode($dataToSend));

            // Send the data to the API
            $curl = curl_init($load_game_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataToSend));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            $response = curl_exec($curl);

            curl_close($curl);

        } else {
            $response = json_encode(["error" => "No saved game found"]);
        }

        return $response;

    } catch (Exception $e) {
        return json_encode(["error" => $e->getMessage()]);
    }
}




// // Usage example:
// session_start();
// $user_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in the session upon login
// if ($user_id !== null) {
//     load_game($user_id);
// } else {
//     echo "User not logged in.";
// }


?>