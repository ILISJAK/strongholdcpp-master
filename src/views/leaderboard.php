<?php
session_start();
include '../../includes/common.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public_html/css/modules/iziToast.min.css">
    <link rel="stylesheet" type="text/css" href="../../public_html/css/styles.scss">
    <script src="../../public_html/js/modules/iziToast.min.js"></script>
    <title>Leaderboard</title>
</head>

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
        z-index: -1;
    }

    .dim-overlay {
        /* Semi-transparent background color */
        background-color: rgba(0, 0, 0, 0.5);
        /* Position the overlay to cover the entire screen */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../../public_html/vid/medieval-european-village-moewalls.com.mp4" type="video/mp4">
    </video>
    <div class="dim-overlay"></div>
    <div class="container">
        <div class="table">
            <div class="table-header">
                <div class="header__item"><a id="player" class="filter__link" href="#">PLAYER</a></div>
                <div class="header__item"><a id="date" class="filter__link" href="#">DATE</a></div>
                <div class="header__item"><a id="score" class="filter__link filter__link--number" href="#">SCORE</a>
                </div>
            </div>
            <div class="table-content">
                <?php
                $leaderboardData = fetchLeaderboardData();
                if (is_array($leaderboardData)) {
                    foreach ($leaderboardData as $row) {
                        echo "<div class=\"table-row\">";
                        echo "<div class=\"table-data\">" . $row["username"] . "</div>";
                        echo "<div class=\"table-data\">" . $row["save_date"] . "</div>";
                        echo "<div class=\"table-data\">" . $row["score"] . "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../public_html/js/tablesort.js"></script>
    <script>
        document.addEventListener('keyup', (e) => {
            if (e.key === 'Escape') {
                window.location.href = '../../index.php';
            }
        });
    </script>
</body>

</html>