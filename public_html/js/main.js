import Game from './modules/Game.js';

window.onload = () => {
    let startGameButton = document.getElementById("start-game");
    let optionsButton = document.getElementById("options");
    let exitButton = document.getElementById("exit");
    let gameOptionsDiv = document.getElementById("game-options");
    let backButton = document.getElementById("back-button");
    let gameOptionsForm = document.getElementById("game-options-form");
    let newGameButton = document.getElementById("play-button");

    let game; // This will hold the Game instance

    startGameButton.addEventListener("click", function () {
        startGameButton.style.display = "none";
        optionsButton.style.display = "none";
        exitButton.style.display = "none";
        gameOptionsDiv.style.display = "block";
    });

    backButton.addEventListener("click", function () {
        gameOptionsDiv.style.display = "none";
        startGameButton.style.display = "block";
        optionsButton.style.display = "block";
        exitButton.style.display = "block";
    });

    gameOptionsForm.addEventListener("submit", function (event) {
        event.preventDefault();

        // Get the number of players from the form
        let numPlayers = document.querySelector("#numPlayers").value;

        console.log('Number of Players:', numPlayers);

        // Create a new game instance
        game = new Game(numPlayers);

        // Send the data to the server using a POST request
        let formData = new FormData();
        formData.append("numPlayers", numPlayers);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../api/start_game.php", true);
        xhr.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                console.log('Response:', this.responseText);

                // Store the number of players in sessionStorage
                sessionStorage.setItem("numPlayers", numPlayers);

                // Redirect to the game instance page
                window.location.href = "../../src/views/game_instance.php";
            } else if (this.readyState === XMLHttpRequest.DONE) {
                console.log('Failed with status:', this.status);
            }
        };
        xhr.send(formData);
    });

    if (newGameButton) {
        newGameButton.addEventListener("click", function () {
            resetGame();
        });
    }

    function resetGame() {
        fetch("../game/reset_game.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json;charset=UTF-8"
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.status === 'success') {
                    console.log('Game reset');
                }
            })
            .catch(error => console.error("There has been a problem with your fetch operation:", error));
    }

};
