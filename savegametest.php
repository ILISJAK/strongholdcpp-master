<!DOCTYPE html>
<html>

<head>
    <title>Game Testing</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#saveGame').click(function () {
                $.ajax({
                    url: 'src/game/save_game.php',
                    method: 'POST',
                    data: { numPlayers: 2 }, // Assume 2 players for now
                    success: function (data) {
                        alert(data);
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Error saving game');
                    }
                });
            });

            $('#loadGame').click(function () {
                $.ajax({
                    url: 'api/load_game.php',
                    method: 'POST',
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Error loading game');
                    }
                });
            });
        });
    </script>
</head>

<body>
    <button id="saveGame">Save Game</button>
    <button id="loadGame">Load Game</button>
    <script>
        let username = <?php echo json_encode($_SESSION['username'] ?? 'default_username'); ?>;
        let user_id = <?php echo json_encode($_SESSION['user_id'] ?? 'default_user_id'); ?>;
        console.log("Username: ", username, " user_id: ", user_id);
    </script>
</body>

</html>