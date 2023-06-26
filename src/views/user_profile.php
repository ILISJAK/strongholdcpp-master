<?php
session_start();
include '../../includes/database.php';

// Check if user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../../public_html/login.html');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

try {
    // Connect to the database
    $db = Database::getConnection();

    // Get user details
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found.');
    }

    // Get high score and games played
    $stmt = $db->prepare("SELECT GetHighScore(:user_id) as highscore, GetGamesPlayed(:user_id) as games_played");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <title>User Profile</title>

    <style>
        body,
        h1,
        h5 {
            font-family: "Raleway", sans-serif
        }

        body,
        html {
            height: 100%
        }

        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }

        .dim-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .profile-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            z-index: 1;
            text-align: center;
        }

        img {
            max-width: 80%;
            height: auto;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../../public_html/vid/medieval-european-village-moewalls.com.mp4" type="video/mp4">
    </video>
    <div class="dim-overlay"></div>

    <div class="w3-card-4 w3-white profile-container">
        <!-- Display the user's profile picture if it exists -->
        <?php if (!empty($user['profile_picture'])): ?>
            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
        <?php else: ?>
            <img src="../../public_html/img/146-1468479_my-profile-icon-blank-profile-picture-circle-hd.png"
                alt="Default Profile Picture">
        <?php endif; ?>
        <h1 class="w3-center">
            <?= htmlspecialchars($user['username']) ?>
        </h1>
        <p>Email:
            <?= htmlspecialchars($user['email']) ?>
        </p>
        <p>Highest Score:
            <?= htmlspecialchars($stats['highscore']) ?>
        </p>
        <p>Games Played:
            <?= htmlspecialchars($stats['games_played']) ?>
        </p>

        <a class="w3-button w3-block w3-black" href="../../index.php">Go back to homepage</a>
    </div>
</body>

</html>