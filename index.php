<?php
session_start();
echo "<script>console.log('Session Data: ', " . json_encode($_SESSION) . ");</script>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public_html/css/modules/iziToast.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
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
            /* Make the video fully cover the screen */
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            /* Prevent content from being hidden */
            z-index: -1;
        }
    </style>
    <title>Index Page</title>
</head>

<body class="bgimg w3-display-container w3-text-white">

    <video autoplay loop muted id="video-background">
        <source src="public_html/vid/medieval-european-village-moewalls.com.mp4" type="video/mp4">
    </video>

    <div class="w3-display-middle w3-jumbo">
        <p>Logo</p>
    </div>
    <div class="w3-display-topleft w3-container w3-xlarge">
        <p><button onclick="window.location.href='src/views/leaderboard.php'" class="w3-button w3-black">View
                Leaderboard</button></p>
        <?php if (isset($_SESSION['username'])): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let username = "<?php echo $_SESSION['username']; ?>";
                    iziToast.show({
                        title: 'Logged in as ',
                        message: username,
                        position: 'topCenter',
                        transitionIn: 'bounceInDown'
                    });
                });</script>
            <p><button onclick="window.location.href='src/views/user_profile.php'"
                    class="w3-button w3-black">Profile</button></p>
            <form action="includes/logout_user.php" method="post">
                <button type="submit" class="w3-button w3-black">Log out</button>
            </form>
        <?php else: ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    iziToast.show({
                        title: 'Logged in as ',
                        message: 'guest',
                        position: 'topCenter',
                        transitionIn: 'bounceInDown'
                    });
                });</script>
            <p><button onclick="window.location.href='public_html/login.html'" class="w3-button w3-black">Log in</button>
            </p>
            <p><button onclick="window.location.href='public_html/register.html'"
                    class="w3-button w3-black">Register</button></p>
        <?php endif; ?>
    </div>
    <div class="w3-display-middle">
        <button class="w3-button w3-black" id="play-button" onclick="playGame()">Play</button>
    </div>
    <div class="w3-display-bottomleft w3-container">
        <p class="w3-xlarge">26-06 | 2023</p>
        <p class="w3-large">StrongholdCPP build v1.0</p>
        <p>powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public_html/js/modules/iziToast.min.js"></script>
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
</body>

</html>