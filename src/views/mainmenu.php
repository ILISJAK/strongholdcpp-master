<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Main Menu</title>
    <link rel="stylesheet" type="text/css" href="../../public_html/css/mainmenu.css">
    <link rel="stylesheet" type="text/css" href="../../public_html/css/modules/iziToast.min.css">

</head>

<body>
    <div id="background"></div>
    <div id="scene">
        <div id="main-menu">
            <h1>StrongholdCPP</h1>
            <button class="button" id="start-game">Start Game</button>
            <button class="button" id="options">Options</button>
            <button class="button" id="exit" onclick="window.location.href = '../../index.php';">Exit</button>

            <div id="game-options" style="display: none;">
                <h2>Game Options</h2>
                <form id="game-options-form" action="start_game.php" method="post">
                    <label for="gamemode">Game Mode:</label>
                    <select id="gamemode" name="gamemode">
                        <option value="classic">Classic</option>
                    </select>

                    <label for="numPlayers">Number of Players:</label>
                    <input type="number" id="numPlayers" name="numPlayers" min="1" max="4">

                    <label for="gameSpeed">Game Speed:</label>
                    <select id="gameSpeed" name="gameSpeed">
                        <option value="normal">Normal</option>
                        <option value="fast">Fast</option>
                        <option value="ultra">Ultra</option>
                    </select>
                    <?php if (isset($_SESSION['towns']) && !empty($_SESSION['towns'])): ?>
                        <button class="button" onclick="window.location.href='game_instance.php'">Continue</button>
                    <?php endif; ?>
                    <input class="button" type="submit" id="play-button" value="New Game">
                </form>
                <button class="button" id="back-button">Back</button>
            </div>
        </div>
    </div>

    <script type="module" src="../../public_html/js/main.js?v=1"></script>
    <script src="../../public_html/js/parallax.js?v=1"></script>
    <script src="../../public_html/js/modules/iziToast.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let username = "<?php echo $_SESSION['username']; ?>";
            iziToast.show({
                title: 'Welcome,',
                message: username,
                position: 'topLeft',
                transitionIn: 'bounceInLeft'
            });
        });
    </script>
</body>

</html>