<?php
session_start();
include 'includes/common.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public_html/css/modules/iziToast.min.css">
    <link rel="stylesheet" type="text/css" href="public_html/css/mainmenu.css">
    <script src="public_html/js/modules/iziToast.min.js"></script>
    <title>Index Page</title>
    <style>
        body,
        html {
            position: fixed;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: darkslategrey;
        }

        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            position: relative;
        }

        #text {
            text-align: center;
            font-size: 24px;
            color: white;
            text-shadow: 2px 2px 5px #000000;

        }

        #play-button {
            position: absolute;
            bottom: 20px;
            font-size: 20px;
            padding: 10px 20px;
        }

        #auth-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .button {
            margin: 0 5px;
            padding: 10px;
        }

        /* Leaderboard Table Styles */
        #leaderboard {
            text-align: center;
            margin-bottom: 400px;
        }

        #leaderboard table {
            border-collapse: collapse;
            margin: auto;
            width: 100%;
            border: 1;
        }

        #leaderboard th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            width: 30%;
            font-size: 14;
        }

        #leaderboard td {
            padding: 10px;
            text-align: center;
            width: 30%;
            font-size: 10pt;
            color: white;
        }

        #leaderboard tr:nth-child(even) {
            background-color: darkslategrey;
        }

        #leaderboard tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="auth-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let username = "<?php echo $_SESSION['username']; ?>";
                        iziToast.show({
                            title: 'Logged in as ',
                            message: username,
                            position: 'topLeft',
                            transitionIn: 'bounceInLeft'
                        });
                    });</script>
                <form action="includes/logout_user.php" method="post">
                    <button type="submit" class="button">Log out</button>
                </form>
            <?php else: ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        iziToast.show({
                            title: 'Logged in as ',
                            message: 'guest',
                            position: 'topLeft',
                            transitionIn: 'bounceInLeft'
                        });
                    });</script>
                <a href="public_html/login.html" class="button">Log in</a>
                <a href="public_html/register.html" class="button">Register</a>
            <?php endif; ?>
        </div>

        <div id="text">
            <div id="leaderboard">
                <h2 style="color: white; text-shadow: 2px 2px 5px #000000;">Leaderboard</h2>
                <table style="background-color: white;">
                    <tr>
                        <th>PLAYER</th>
                        <th>DATE</th>
                        <th>SCORE</th>
                    </tr>
                    <?php
                    $leaderboardData = fetchLeaderboardData();
                    if (is_array($leaderboardData)) {
                        foreach ($leaderboardData as $row) {
                            echo "<tr><td>" . $row["username"] . "</td><td>" . $row["save_date"] . "</td><td>" . $row["score"] . "</td></tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <button class="button" id="play-button" onclick="playGame()">Play</button>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function playGame() {
                <?php if (isset($_SESSION['username'])): ?>
                    window.location.href = 'src/views/mainmenu.php';
                <?php else: ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'You must be logged in to play.',
                    });
                <?php endif; ?>
            }
        </script>
    </div>
</body>

</html>